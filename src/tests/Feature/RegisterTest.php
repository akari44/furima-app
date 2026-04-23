<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    /**【TEST 1-1】名前が未入力の場合、バリデーションエラーと文言確認**/

    public function test_name_is_required_for_registration()
    {
        $response = $this->from('/register')->post('/register', [
            'name' => '',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect('/register');
        $response->assertSessionHasErrors(['name']);
        
        $response = $this->followRedirects($response);
        $response->assertSee('お名前を入力してください');
        
       
    }

    /**【TEST 1-2】メールが未入力の場合、バリデーションエラーと文言確認**/
    public function test_email_is_required_for_registration()
    {
        $response = $this->from('/register')->post('/register', [
            'name' => 'テスト太郎',
            'email' => '',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect('/register');
        $response->assertSessionHasErrors(['email']);

        $response = $this->followRedirects($response);
        $response->assertSee('メールアドレスを入力してください');
    }

    /**【TEST 1-3】メールが未入力の場合、バリデーションエラーと文言確認**/
    public function test_password_is_required_for_registration()
    {
        $response = $this->from('/register')->post('/register', [
            'name' => 'テスト太郎',
            'email' => 'test@example.com',
            'password' => '',
            'password_confirmation' => '',
        ]);

        $response->assertRedirect('/register');
        $response->assertSessionHasErrors(['password']);

        $response = $this->followRedirects($response);
        $response->assertSee('パスワードを入力してください');

    }

    /**【TEST 1-4】パスワードが7文字以下の場合、バリデーションエラーと文言確認**/
    public function test_password_must_be_at_least_8_characters()
    {
        $response = $this->from('/register')->post('/register', [
            'name' => 'テスト太郎',
            'email' => 'test@example.com',
            'password' => 'pass123',
            'password_confirmation' => 'pass123',
        ]);

        $response->assertRedirect('/register');
        $response->assertSessionHasErrors(['password']);

        $response = $this->followRedirects($response);
        $response->assertSee('パスワードは8文字以上で入力してください');
    }

    /**【TEST 1-5】パスワード確認が一致しない場合、バリデーションエラーになる**/
    public function test_password_confirmation_must_match(): void
    {
        $response = $this->from('/register')->post('/register', [
            'name' => 'テスト太郎',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'errors123',
        ]);

        $response->assertRedirect('/register');
        $response->assertSessionHasErrors(['password']);

        $response = $this->followRedirects($response);
        $response->assertSee('パスワードと一致しません');
    }

     /** 【TEST 1-6】全ての項目が入力されたら会員登録でき、メール認証誘導画面へ遷移する**/
    public function test_user_can_register_successfully(): void
    {
        $response = $this->post('/register', [
            'name' => 'テスト太郎',
            'email' => 'test_taro@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect(route('verification.notice'));

        $this->assertDatabaseHas('users', [
            'name' => 'テスト太郎',
            'email' => 'test_taro@example.com',
        ]);

        $this->assertAuthenticated();
    }

    
}
