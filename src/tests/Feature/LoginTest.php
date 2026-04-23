<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    /**【TEST 2-1】メアドが未入力の場合、バリデーションエラーと文言確認**/
    public function test_email_is_required_for_login()
    {
        $response = $this->from('/login')->post('/login', [
            'email' => '',
            'password' => 'password1234',
        ]);

    
        $response->assertRedirect('/login');
        $response->assertSessionHasErrors(['email']);

        $response = $this->followRedirects($response);
        $response->assertSee('メールアドレスを入力してください');
    }

     /**【TEST 2-2】パスワードが未入力の場合、バリデーションエラーと文言確認**/
    public function test_password_is_required_for_login()
    {
        $response = $this->from('/login')->post('/login', [
            'email' => 'test@example.com',
            'password' => '',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHasErrors(['password']);

        $response = $this->followRedirects($response);
        $response->assertSee('パスワードを入力してください');
    }
    
     /**【TEST 2-3】パスワードが未入力の場合、ログイン不可とバリデーション文言確認**/
    public function test_user_cannot_login_with_invalid_credentials()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password1234'),
        ]);

        $response = $this->from('/login')->post('/login', [
            'email' => 'test@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHasErrors();
        $this->assertGuest();

        $response = $this->followRedirects($response);
        $response->assertSee('ログイン情報が登録されていません');
    }

    /**【TEST 2-4】正しい情報が入力された場合のグイン処理①（メール未認証ユーザー）**/
   public function test_unverified_user_is_redirected_to_verification_notice()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password1234'),
            'email_verified_at' => null,
        ]);

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password1234',
        ]);

        $this->assertAuthenticated();

        $response->assertRedirect(route('verification.notice'));
    }

    /**【TEST 2-4】正しい情報が入力された場合のグイン処理②（プロフ未登録ユーザー）**/
    public function test_user_without_profile_is_redirected_to_profile_edit()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password1234'),
            'email_verified_at' => now(),
            'postal_code' => null, 
        ]);

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password1234',
        ]);

        $this->assertAuthenticated();

        $response->assertRedirect(route('profile.edit'));
    }

    /**【TEST 2-4】正しい情報が入力された場合のグイン処理③（プロフ登録済ユーザー）**/
    public function test_verified_user_with_profile_is_redirected_to_home(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password1234'),
            'email_verified_at' => now(),
            'postal_code' => '123-4567',
        ]);

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password1234',
        ]);

        $this->assertAuthenticated();

        $response->assertRedirect('/?tab=mylist');
    }

     /**【TEST 3】ログアウトができ、ログイン画面に移動**/
    public function test_user_can_logout(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post('/logout');
        $this->assertGuest();

        $response->assertRedirect('/login');
    }   
}
