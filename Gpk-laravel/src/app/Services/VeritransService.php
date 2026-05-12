<?php

namespace App\Services;

use App\Models\VeritransLog;
use App\Models\Reservation;
use App\Models\Order;
use App\Consts\ReservationConst;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;

class VeritransService
{
    /**
     * トランザクションステータスコード
     */
    public const TXN_SUCCESS_CODE = 'success';
    public const TXN_PENDING_CODE = 'pending';
    public const TXN_FAILURE_CODE = 'failure';

    /**
     * Veritrans決済処理
     *
     * @param int $user_id ユーザーID
     * @param Reservation $reservation 予約情報
     * @param array $card_data カード情報
     * @param int $amount 決済金額
     * @return bool
     * @throws ValidationException
     * @throws \Exception
     */
    public function processPayment($user_id, Reservation $reservation, array $card_data, int $amount): bool
    {
        try {
            // Veritrans設定読み込み
            $config_path = $this->getConfigPath();
            
            // SDKが存在するかチェック
            if (!class_exists('\tgMdk\TGMDK_Config')) {
                // SDK未インストール時はモック処理（開発用）
                Log::warning('Veritrans SDK not found. Using mock payment.');
                return $this->mockPayment($user_id, $reservation, $amount);
            }

            // Veritrans設定読み込み
            \tgMdk\TGMDK_Config::getInstance($config_path);
            
            // トランザクション作成
            $transaction = new \tgMdk\TGMDK_Transaction();
            $request_data = new \tgMdk\dto\CardAuthorizeRequestDto();
            
            // 注文ID生成（ユニーク）
            $orderId = $user_id . '-' . $reservation->id . '-' . date("YmdHis");
            
            // リクエストデータ設定
            $request_data->setOrderId($orderId);
            $request_data->setAmount($amount);
            
            // カード情報設定
            if (isset($card_data['token']) && $card_data['token']) {
                // トークン決済（推奨）
                $request_data->setToken($card_data['token']);
            } else {
                // 直接カード情報（非推奨だがテスト用）
                if (isset($card_data['card_number'])) {
                    $request_data->setCardNumber($card_data['card_number']);
                }
                if (isset($card_data['card_expire'])) {
                    $request_data->setCardExpire($card_data['card_expire']);
                }
                if (isset($card_data['security_code'])) {
                    $request_data->setSecurityCode($card_data['security_code']);
                }
            }
            
            // API実行
            $response_data = $transaction->execute($request_data);
            
            if (!$response_data) {
                throw new \Exception('決済APIからレスポンスがありません');
            }
            
            if ($response_data instanceof \tgMdk\dto\CardAuthorizeResponseDto) {
                // 結果取得
                $txn_status = $response_data->getMStatus();
                $txn_result_code = $response_data->getVResultCode();
                $error_message = $response_data->getMerrMsg();
                
                // ログ保存（必須）
                VeritransLog::create([
                    'user_id' => $user_id,
                    'reservation_id' => $reservation->id,
                    'order_id' => $orderId,
                    'type' => 1,
                    'txn_status' => $txn_status,
                    'txn_result_code' => $txn_result_code,
                    'err_message' => $error_message,
                ]);
                
                // 結果判定
                if (self::TXN_SUCCESS_CODE === $txn_status) {
                    // 成功
                    $center_reference_number = $response_data->getCenterReferenceNumber();
                    
                    // 予約・注文のステータスを更新
                    $reservation->update(['status' => ReservationConst::STATUS_RESERVED]);
                    
                    Order::where('reservation_id', $reservation->id)
                        ->update(['payment_status' => 1]);
                    
                    Log::info('Payment Success', [
                        'orderId' => $orderId,
                        'reservation_id' => $reservation->id,
                        'center_reference_number' => $center_reference_number
                    ]);
                    
                    return true;
                    
                } else if (self::TXN_PENDING_CODE === $txn_status) {
                    // ペンディング
                    throw ValidationException::withMessages([
                        'card_error' => 'カード決済が保留中です。しばらくお待ちください。'
                    ]);
                    
                } else {
                    // 失敗
                    throw ValidationException::withMessages([
                        'card_error' => "カード決済でエラーが発生しました: {$error_message}"
                    ]);
                }
            } else {
                throw new \Exception('決済処理でエラーが発生しました');
            }
            
        } catch (ValidationException $e) {
            // バリデーションエラーはそのまま投げる
            throw $e;
        } catch (\Exception $e) {
            // その他のエラーはログに記録
            Log::error('Payment Exception', [
                'user_id' => $user_id,
                'reservation_id' => $reservation->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // ログ保存（エラー時も）
            try {
                VeritransLog::create([
                    'user_id' => $user_id,
                    'reservation_id' => $reservation->id,
                    'order_id' => $user_id . '-' . $reservation->id . '-' . date("YmdHis"),
                    'type' => 1,
                    'txn_status' => self::TXN_FAILURE_CODE,
                    'txn_result_code' => 'EXCEPTION',
                    'err_message' => $e->getMessage(),
                ]);
            } catch (\Exception $log_error) {
                Log::error('Failed to save Veritrans log', ['error' => $log_error->getMessage()]);
            }
            
            throw new \Exception('決済処理中にエラーが発生しました。管理者にお問い合わせください。');
        }
    }

    /**
     * 設定ファイルパス取得
     *
     * @return string
     */
    private function getConfigPath(): string
    {
        // 開発環境
        $dev_path = base_path('local_packages/veritrans-tgmdk/src/tgMdk/3GPSMDK.properties');
        
        if (file_exists($dev_path)) {
            return $dev_path;
        }
        
        // 本番環境（vendor経由）
        $prod_path = base_path('vendor/veritrans/tgmdk/src/tgMdk/3GPSMDK.properties');
        
        if (file_exists($prod_path)) {
            return $prod_path;
        }
        
        // デフォルト（開発環境想定）
        return $dev_path;
    }

    /**
     * モック決済処理（SDK未インストール時用）
     *
     * @param int $user_id
     * @param Reservation $reservation
     * @param int $amount
     * @return bool
     */
    private function mockPayment($user_id, Reservation $reservation, int $amount): bool
    {
        // モック決済ログ保存
        VeritransLog::create([
            'user_id' => $user_id,
            'reservation_id' => $reservation->id,
            'order_id' => $user_id . '-' . $reservation->id . '-' . date("YmdHis"),
            'type' => 1,
            'txn_status' => self::TXN_SUCCESS_CODE,
            'txn_result_code' => 'MOCK_SUCCESS',
            'err_message' => 'Mock payment (SDK not installed)',
        ]);
        
        // 予約・注文のステータスを更新
        $reservation->update(['status' => ReservationConst::STATUS_RESERVED]);
        
        Order::where('reservation_id', $reservation->id)
            ->update(['payment_status' => 1]);
        
        Log::info('Mock Payment Success', [
            'reservation_id' => $reservation->id,
            'amount' => $amount
        ]);
        
        return true;
    }
}

