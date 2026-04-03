<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ItemTest extends TestCase
{
    use RefreshDatabase;

    public function test_items_index_page_is_displayed(): void
    {
        // ログインするユーザーを作成
        $loginUser = User::factory()->create();
        // 別の出品者ユーザーを作成
        $seller = User::factory()->create();
        // 一覧に表示される商品を1件作成
        Item::factory()->count(3)->create([
            'seller_id' => $seller->id,
        ]);
        // ログイン状態で商品一覧ページにアクセス
        $response = $this->actingAs($loginUser)->get('/');
        // ページが正常に開くことを確認
        $response->assertStatus(200);
    }

    public function test_items_index_page_displays_item_name(): void
    {
        $loginUser = User::factory()->create();
        $seller = User::factory()->create();

        Item::factory()->create([
            'seller_id' => $seller->id,
            'item_name' => 'テスト商品',
        ]);

        $response = $this->actingAs($loginUser)->get('/');

        // 商品名が画面に表示されることを確認
        $response->assertStatus(200);
        $response->assertSee('テスト商品');
    }
     /**
     * 大量データでも一覧ページが表示できるかテスト
     */
    public function test_items_index_page_is_displayed_with_large_data(): void
    {
        $loginUser = User::factory()->create();
        $sellers = User::factory()->count(100)->create();
         // 300件の商品をランダムな出品者に紐づけて作る
        foreach (range(1, 300) as $i) {
            Item::factory()->create([
                'seller_id' => $sellers->random()->id,
            ]);
        }

        $response = $this->actingAs($loginUser)->get('/');

        $response->assertStatus(200);
    }

    /**
 * 未ログインユーザーはマイページにアクセスできない
 */
    public function test_guest_cannot_access_mypage(): void
    {
        // ログインしていない状態でマイページにアクセス
        $response = $this->get('/mypage');

        // ログインページへリダイレクトされることを確認
        $response->assertRedirect('/login');
    }

        /**
     * 商品詳細ページが表示される
     */
    public function test_item_detail_page_is_displayed(): void
    {
        // 出品者ユーザーを作る
        $seller = User::factory()->create();

        // 商品を1件作る
        $item = Item::factory()->create([
            'seller_id' => $seller->id,
            'item_name' => '詳細テスト商品',
        ]);

        // 商品詳細ページにアクセス
        $response = $this->get('/item/' . $item->id);

        // ページが正常表示されることを確認
        $response->assertStatus(200);

        // 商品名が画面に表示されることを確認
        $response->assertSee('詳細テスト商品');
    }
}