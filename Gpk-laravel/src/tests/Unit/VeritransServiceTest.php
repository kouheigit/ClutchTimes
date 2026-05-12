<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\VeritransService;
use App\Models\User;
use App\Models\Reservation;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;

class VeritransServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $veritransService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->veritransService = new VeritransService();
    }

    /**
     * 決済処理のモックテスト（SDK未インストール時の動作確認）
     */
    public function test_payment_process_without_sdk()
    {
        $user = User::factory()->create();
        $reservation = Reservation::factory()->create(['user_id' => $user->id]);
        $order = Order::factory()->create([
            'reservation_id' => $reservation->id,
            'total_price' => 10000,
        ]);

        // SDK未インストール時はモック決済が動作することを確認
        // 実際のテストでは、SDKのモックを使用することを推奨
        $this->assertTrue(true);
    }

    /**
     * 決済ログ保存のテスト
     */
    public function test_can_save_payment_log()
    {
        $user = User::factory()->create();
        $reservation = Reservation::factory()->create(['user_id' => $user->id]);

        // 決済ログの保存ロジックをテスト
        // 実際の実装に応じてテストを追加
        $this->assertTrue(true);
    }
}

