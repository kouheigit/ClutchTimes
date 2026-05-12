<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Illuminate\Support\Facades\Hash;

class UserApiTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    /**
     * ユーザー情報取得のテスト
     */
    public function test_can_get_user_info()
    {
        Sanctum::actingAs($this->user);

        $response = $this->getJson('/api/user');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'email',
                ]
            ]);
    }

    /**
     * ユーザー情報更新のテスト
     */
    public function test_can_update_user_info()
    {
        Sanctum::actingAs($this->user);

        $data = [
            'name' => '更新された名前',
            'last_name' => '山田',
            'first_name' => '太郎',
        ];

        $response = $this->putJson('/api/user', $data);

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'name' => '更新された名前',
                ]
            ]);

        $this->assertDatabaseHas('users', [
            'id' => $this->user->id,
            'name' => '更新された名前',
        ]);
    }

    /**
     * パスワード更新のテスト
     */
    public function test_can_update_password()
    {
        Sanctum::actingAs($this->user);

        $data = [
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ];

        $response = $this->putJson('/api/user', $data);

        $response->assertStatus(200);

        $this->user->refresh();
        $this->assertTrue(Hash::check('newpassword123', $this->user->password));
    }

    /**
     * メールアドレス重複チェックのテスト
     */
    public function test_cannot_update_to_existing_email()
    {
        Sanctum::actingAs($this->user);

        $otherUser = User::factory()->create();

        $data = [
            'email' => $otherUser->email,
        ];

        $response = $this->putJson('/api/user', $data);

        $response->assertStatus(422);
    }

    /**
     * 認証なしではアクセスできないテスト
     */
    public function test_cannot_access_without_auth()
    {
        $response = $this->getJson('/api/user');

        $response->assertStatus(401);
    }
}

