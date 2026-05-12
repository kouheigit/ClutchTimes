<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use App\Models\User;
use App\Models\Service;
use App\Models\Hotel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

class ServiceApiTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    /**
     * サービス一覧取得のテスト
     */
    public function test_can_get_services()
    {
        Sanctum::actingAs($this->user);

        Service::factory()->count(5)->create();

        $response = $this->getJson('/api/services');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'title',
                        'price',
                        'service_options',
                    ]
                ]
            ]);
    }

    /**
     * サービス詳細取得のテスト
     */
    public function test_can_get_service_detail()
    {
        Sanctum::actingAs($this->user);

        $service = Service::factory()->create();

        $response = $this->getJson("/api/services/{$service->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'title',
                    'body',
                    'price',
                    'service_options',
                ]
            ]);
    }

    /**
     * ステータスフィルターのテスト
     */
    public function test_can_filter_services_by_status()
    {
        Sanctum::actingAs($this->user);

        Service::factory()->create(['status' => 1]);
        Service::factory()->create(['status' => 0]);

        $response = $this->getJson('/api/services?status=1');

        $response->assertStatus(200);
        $response->assertJsonCount(1, 'data');
    }

    /**
     * タブフィルターのテスト
     */
    public function test_can_filter_services_by_tab()
    {
        Sanctum::actingAs($this->user);

        Service::factory()->preReservation()->create();
        Service::factory()->onSiteOrder()->create();

        $response = $this->getJson('/api/services?tab=1');

        $response->assertStatus(200);
        $response->assertJsonCount(1, 'data');
    }
}

