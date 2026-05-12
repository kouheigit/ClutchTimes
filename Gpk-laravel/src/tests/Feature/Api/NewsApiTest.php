<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use App\Models\News;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;

class NewsApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * お知らせ一覧取得のテスト（公開API）
     */
    public function test_can_get_news_list()
    {
        News::factory()->count(5)->create([
            'status' => 1,
            'publish_date' => Carbon::now()->subDays(1),
        ]);

        $response = $this->getJson('/api/news');

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
     * お知らせ詳細取得のテスト（公開API）
     */
    public function test_can_get_news_detail()
    {
        $news = News::factory()->create([
            'status' => 1,
            'publish_date' => Carbon::now()->subDays(1),
        ]);

        $response = $this->getJson("/api/news/{$news->id}");

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
     * 未公開のお知らせは取得できないテスト
     */
    public function test_cannot_get_unpublished_news()
    {
        $news = News::factory()->create([
            'status' => 0,
            'publish_date' => Carbon::now()->subDays(1),
        ]);

        $response = $this->getJson("/api/news/{$news->id}");

        $response->assertStatus(404);
    }

    /**
     * 未来の公開日のお知らせは取得できないテスト
     */
    public function test_cannot_get_future_news()
    {
        $news = News::factory()->create([
            'status' => 1,
            'publish_date' => Carbon::now()->addDays(1),
        ]);

        $response = $this->getJson("/api/news/{$news->id}");

        $response->assertStatus(404);
    }

    /**
     * 検索機能のテスト
     */
    public function test_can_search_news()
    {
        News::factory()->create([
            'title' => 'テストお知らせ1',
            'status' => 1,
            'publish_date' => Carbon::now()->subDays(1),
        ]);

        News::factory()->create([
            'title' => '別のお知らせ',
            'status' => 1,
            'publish_date' => Carbon::now()->subDays(1),
        ]);

        $response = $this->getJson('/api/news?search=テスト');

        $response->assertStatus(200);
        $this->assertGreaterThan(0, count($response->json('data')));
    }
}

