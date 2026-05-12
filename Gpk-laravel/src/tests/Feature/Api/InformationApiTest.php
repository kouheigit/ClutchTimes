<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use App\Models\Information;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;

class InformationApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 情報一覧取得のテスト（公開API）
     */
    public function test_can_get_information_list()
    {
        Information::factory()->count(5)->create([
            'status' => 1,
            'publish_date' => Carbon::now()->subDays(1),
        ]);

        $response = $this->getJson('/api/information');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'title',
                        'body',
                        'publish_date',
                    ]
                ]
            ]);
    }

    /**
     * 情報詳細取得のテスト（公開API）
     */
    public function test_can_get_information_detail()
    {
        $information = Information::factory()->create([
            'status' => 1,
            'publish_date' => Carbon::now()->subDays(1),
        ]);

        $response = $this->getJson("/api/information/{$information->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'title',
                    'body',
                    'publish_date',
                ]
            ]);
    }

    /**
     * 未公開の情報は取得できないテスト
     */
    public function test_cannot_get_unpublished_information()
    {
        $information = Information::factory()->create([
            'status' => 0,
            'publish_date' => Carbon::now()->subDays(1),
        ]);

        $response = $this->getJson("/api/information/{$information->id}");

        $response->assertStatus(404);
    }

    /**
     * 検索機能のテスト
     */
    public function test_can_search_information()
    {
        Information::factory()->create([
            'title' => 'テスト情報1',
            'status' => 1,
            'publish_date' => Carbon::now()->subDays(1),
        ]);

        Information::factory()->create([
            'title' => '別の情報',
            'status' => 1,
            'publish_date' => Carbon::now()->subDays(1),
        ]);

        $response = $this->getJson('/api/information?search=テスト');

        $response->assertStatus(200);
        $this->assertGreaterThan(0, count($response->json('data')));
    }
}

