<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Comment;
use App\Models\Item;
use App\Models\User;

class CommentTest extends TestCase
{
    use RefreshDatabase;

     //【TestCase9-1】ログイン済みユーザーはコメントを送信できる
    public function test_user_can_post_comment()
    {
        $user = User::factory()->create();
        $seller = User::factory()->create();
        $item = Item::factory()->create([
            'seller_id' => $seller->id,
        ]);

        $response = $this->actingAs($user)->post('/item/' . $item->id . '/comments', [
            'body' => '値引き可能ですか？',
        ]);
        $response->assertStatus(302);

        $this->assertDatabaseHas('comments', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'body' => '値引き可能ですか？',
        ]);
    }

    //【TestCase9-2】未ログインユーザーはコメントできない
    public function test_guest_cannot_post_comment()
    {
        $seller = User::factory()->create();
        $item = Item::factory()->create([
            'seller_id' => $seller->id,
        ]);

        // 未ログインで送信
        $response = $this->post('/item/' . $item->id . '/comments', [
            'body' => '値引き可能です。',
        ]);

        $response->assertRedirect('/login');

        $this->assertDatabaseMissing('comments', [
            'body' => '値引き可能です。',
        ]);
    }

    //【TestCase9-3】コメント未入力でエラーになる
    public function test_comment_is_required()
    {
        $user = User::factory()->create();
        $seller = User::factory()->create();
        $item = Item::factory()->create([
            'seller_id' => $seller->id,
        ]);

        $response = $this->actingAs($user)
            ->from('/item/' . $item->id) 
            ->post('/item/' . $item->id . '/comments', [
                'body' => '',
            ]);

        
        $response->assertRedirect('/item/' . $item->id);
        $response->assertSessionHasErrors(['body']);
        $this->followRedirects($response)
            ->assertSee('コメントを入力してください。');
    }

    //【TestCase9-4】コメントが255文字以上でエラーになる
    public function test_comment_must_be_within_255_characters()
    {
        $user = User::factory()->create();
        $seller = User::factory()->create();
        $item = Item::factory()->create([
            'seller_id' => $seller->id,
        ]);

        $longComment = str_repeat('あ', 256);

        $response = $this->actingAs($user)
            ->from('/item/' . $item->id)
            ->post('/item/' . $item->id . '/comments', [
                'body' => $longComment,
            ]);

        $response->assertRedirect('/item/' . $item->id);

        $response->assertSessionHasErrors(['body']);

        $this->followRedirects($response)
            ->assertSee('コメントは225文字以内で入力してください。');
    }
}
