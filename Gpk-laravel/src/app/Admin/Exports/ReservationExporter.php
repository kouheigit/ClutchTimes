<?php

namespace App\Admin\Exports;

use App\Models\Reservation;
use App\Consts\ReservationConst;
use Encore\Admin\Grid\Exporters\AbstractExporter;

class ReservationExporter extends AbstractExporter
{
    /**
     * エクスポート処理
     *
     * @return void
     */
    public function export()
    {
        $filename = '予約一覧_' . date('YmdHis') . '.csv';
        
        $headers = [
            'Content-Encoding' => 'UTF-8',
            'Content-Type' => 'text/csv;charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];
        
        $callback = function () {
            $file = fopen('php://output', 'w');
            
            // BOM付きUTF-8（Excelで文字化けしないように）
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // ヘッダー行
            fputcsv($file, [
                'ID',
                'ユーザー名',
                'ユーザーメール',
                'オーナー名',
                'ホテル名',
                'チェックイン日',
                'チェックアウト日',
                'チェックイン時刻',
                'チェックアウト時刻',
                '宿泊日数',
                '代表者名',
                '大人人数',
                '子供人数',
                '犬頭数',
                '備考',
                '入室番号',
                '決済方法',
                'ステータス',
                '作成日時',
                '更新日時',
            ]);
            
            // データ行
            $this->getData()->chunk(100, function ($records) use ($file) {
                foreach ($records as $record) {
                    $reservation = Reservation::with(['user', 'owner', 'hotel'])->find($record->id);
                    
                    if (!$reservation) {
                        continue;
                    }
                    
                    fputcsv($file, [
                        $reservation->id,
                        $reservation->user ? $reservation->user->name : '',
                        $reservation->user ? $reservation->user->email : '',
                        $reservation->owner ? $reservation->owner->name : '',
                        $reservation->hotel ? $reservation->hotel->name : '',
                        $reservation->checkin_date ? $reservation->checkin_date->format('Y/m/d') : '',
                        $reservation->checkout_date ? $reservation->checkout_date->format('Y/m/d') : '',
                        $reservation->checkin_time ? $reservation->checkin_time->format('H:i') : '',
                        $reservation->checkout_time ? $reservation->checkout_time->format('H:i') : '',
                        $reservation->days,
                        $reservation->name ?? '',
                        $reservation->adult,
                        $reservation->child,
                        $reservation->dog,
                        $reservation->note ?? '',
                        $reservation->room_key ?? '',
                        $reservation->payment == 0 ? '現地払い' : 'クレジット',
                        ReservationConst::STATUS_LIST[$reservation->status] ?? '',
                        $reservation->created_at ? $reservation->created_at->format('Y/m/d H:i:s') : '',
                        $reservation->updated_at ? $reservation->updated_at->format('Y/m/d H:i:s') : '',
                    ]);
                }
            });
            
            fclose($file);
        };
        
        response()->stream($callback, 200, $headers)->send();
        exit;
    }
}

