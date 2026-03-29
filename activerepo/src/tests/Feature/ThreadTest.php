<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Thread;

class ThreadTest extends TestCase
{
    use RefreshDatabase; // 🔥 テストごとにデータベースをリセット

    /** @test */
    public function index_page_loads_correctly()
    {
        $response = $this->get(route('test.index'));
        $response->assertStatus(200);
        $response->assertViewIs('test.index');
    }

    /** @test */
    public function search_function_works_correctly()
    {
        Thread::factory()->create(['title' => 'Laravel Tips']);

        $response = $this->get(route('test.index', ['search' => 'Laravel']));
        $response->assertStatus(200);
        $response->assertSee('Laravel Tips');
    }

    /** @test */
    public function order_by_asc_and_desc_works()
    {
        Thread::factory()->create(['title' => 'A First Thread', 'id' => 1]);
        Thread::factory()->create(['title' => 'Z Last Thread', 'id' => 2]);

        // 昇順 (ASC)
        $response = $this->get(route('test.index', ['order' => 'asc']));
        $response->assertSeeInOrder(['A First Thread', 'Z Last Thread']);

        // 降順 (DESC)
        $response = $this->get(route('test.index', ['order' => 'desc']));
        $response->assertSeeInOrder(['Z Last Thread', 'A First Thread']);
    }


    public function can_create_thread()
    {
        $response = $this->post(route('test.store'), [
            'title' => '新しいタイトル',
            'name' => 'テストユーザー',
            'comment' => 'これはテストコメントです。',
        ]);

        $response->assertRedirect('test'); // 成功したらリダイレクト
        $this->assertDatabaseHas('threads', ['title' => '新しいスレッド']); // DB にデータがあるか確認
    }

}
