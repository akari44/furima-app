<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Item;
use App\Models\ItemImage;
use App\Models\Like;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ItemTest extends TestCase
{
    use RefreshDatabase;
    //【テスト4-1】全商品を取得できる
    public function test_all_items_are_displayed_on_index_page()
        {
            $loginUser = User::factory()->create();
            $seller1 = User::factory()->create();
            $seller2 = User::factory()->create();

            Item::factory()->create([
                'seller_id' => $seller1->id,
                'item_name' => '商品A',
            ]);

            Item::factory()->create([
                'seller_id' => $seller2->id,
                'item_name' => '商品B',
            ]);

            $response = $this->actingAs($loginUser)->get('/');

            $response->assertStatus(200);
            $response->assertSee('商品A');
            $response->assertSee('商品B');
        }

     //【テスト4-2】購入済商品は「Sold」と表示される
        public function test_sold_label_is_displayed_for_sold_items_on_index_page()
    {
        $loginUser = User::factory()->create();
        $seller = User::factory()->create();

        Item::factory()->create([
            'seller_id' => $seller->id,
            'item_name' => '売り切れ商品',
            'status' => 'sold',
        ]);

        $response = $this->actingAs($loginUser)->get('/');

        $response->assertStatus(200);
        $response->assertSee('売り切れ商品');
        $response->assertSee('Sold');
    }

   //【テスト4-3】自分が出品した商品は表示されない 
   public function test_own_items_are_not_displayed_on_index_page()
    {
        $loginUser = User::factory()->create();
        $otherSeller = User::factory()->create();

        Item::factory()->create([
            'seller_id' => $loginUser->id,
            'item_name' => '自分の商品',
        ]);

        Item::factory()->create([
            'seller_id' => $otherSeller->id,
            'item_name' => '他人の商品',
        ]);

        $response = $this->actingAs($loginUser)->get('/');

        $response->assertStatus(200);
        $response->assertDontSee('自分の商品');
        $response->assertSee('他人の商品');
    }

   //【テスト5-1】マイリストにはいいねした商品だけが表示される
   public function test_only_liked_items_are_displayed_on_mylist()
    {
        $loginUser = User::factory()->create();
        $seller = User::factory()->create();

        $likedItem = Item::factory()->create([
            'seller_id' => $seller->id,
            'item_name' => 'いいねした商品',
        ]);

        $notLikedItem = Item::factory()->create([
            'seller_id' => $seller->id,
            'item_name' => 'いいねしてない商品',
        ]);

        Like::create([
            'user_id' => $loginUser->id,
            'item_id' => $likedItem->id,
        ]);

        $response = $this->actingAs($loginUser)->get('/?tab=mylist');

        $response->assertStatus(200);
        $response->assertSee('いいねした商品');
        $response->assertDontSee('いいねしてない商品');
    }

   //【テスト5-2】マイリストの中の購入済み商品は「Sold」と表示される
   public function test_sold_label_is_displayed_for_sold_items_on_mylist(): void
    {
        $loginUser = User::factory()->create();
        $seller = User::factory()->create();

        $likedSoldItem = Item::factory()->create([
            'seller_id' => $seller->id,
            'item_name' => 'いいね済み売り切れ商品',
            'status' => 'sold',
        ]);

        Like::create([
            'user_id' => $loginUser->id,
            'item_id' => $likedSoldItem->id,
        ]);

        $response = $this->actingAs($loginUser)->get('/?tab=mylist');

        $response->assertStatus(200);
        $response->assertSee('いいね済み売り切れ商品');
        $response->assertSee('Sold');
    } 

   //【テスト5-3】マイリストページに未認証の場合は何も表示されない
   public function test_guest_sees_no_items_on_mylist()
    {
        $seller = User::factory()->create();
        $user = User::factory()->create();

        $item = Item::factory()->create([
            'seller_id' => $seller->id,
            'item_name' => '商品A',
        ]);

        Like::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        // 未ログインのままマイリストを開く
        $response = $this->get('/?tab=mylist');

        $response->assertStatus(200);
        $response->assertDontSee('商品A');
    }

   //【テスト6-1】商品名で部分一致検索ができる 
    public function test_items_can_be_searched_by_partial_name()
    {
        $loginUser = User::factory()->create();
        $seller = User::factory()->create();

        Item::factory()->create([
            'seller_id' => $seller->id,
            'item_name' => 'メロンパン',
        ]);

        Item::factory()->create([
            'seller_id' => $seller->id,
            'item_name' => 'クロワッサン',
        ]);

        $response = $this->actingAs($loginUser)->get('/?keyword=パン');

        $response->assertStatus(200);
        $response->assertSee('メロンパン');
        $response->assertDontSee('クロワッサン');
    }

   //【テスト6-2】検索状態がマイリストでも保持されている
   public function test_search_keyword_is_kept_on_mylist(): void
    {
        $loginUser = User::factory()->create();
        $seller = User::factory()->create();

        $likedItem = Item::factory()->create([
            'seller_id' => $seller->id,
            'item_name' => '塩パン',
        ]);

        Like::create([
            'user_id' => $loginUser->id,
            'item_id' => $likedItem->id,
        ]);

        $response = $this->actingAs($loginUser)->get('/?tab=mylist&keyword=パン');

        $response->assertStatus(200);
        $response->assertSee('塩パン');
        $response->assertSee('value="パン"', false);
    }

   //【テスト7-1】商品に必要な情報がすべて表示される 
    public function test_item_detail_page_displays_required_information()
    {
        $seller = User::factory()->create();
        $commentUser = User::factory()->create();

        $item = Item::factory()->create([
            'seller_id' => $seller->id,
            'item_name' => '確認あんぱん',
            'brand' => '西村屋',
            'price' => 1000,
            'description' => '美味しいあんぱん',
            'condition' => 'good',
        ]);

        ItemImage::create([
            'item_id' => $item->id,
            'image_path' => 'item_images/test_img.jpg',
        ]);

        Like::create([
            'user_id' => $commentUser->id,
            'item_id' => $item->id,
        ]);

        Comment::create([
            'item_id' => $item->id,
            'user_id' => $commentUser->id,
            'body' => 'とてもおいしいです',
        ]);

        $category1 = Category::create(['category_name' => 'キッチン']);
        $category2 = Category::create(['category_name' => 'ハンドメイド']);
        $item->categories()->attach([$category1->id, $category2->id]);

        $response = $this->get('/item/' . $item->id);

        $response->assertStatus(200);
        $response->assertSee('確認あんぱん');
        $response->assertSee('西村屋');
        $response->assertSee('1,000');
        $response->assertSee('美味しいあんぱん');
        $response->assertSee('キッチン');
        $response->assertSee('ハンドメイド');
        $response->assertSee('とてもおいしいです');
        $response->assertSee($commentUser->name);
        $response->assertSee('item_images/test_img.jpg');
    }

   //【テスト7-2】複数選択されたカテゴリが表示される
   public function test_multiple_categories_are_displayed_on_item_detail_page()
    {
        $seller = User::factory()->create();

        $item = Item::factory()->create([
            'seller_id' => $seller->id,
            'item_name' => '確認テレビ',
        ]);

        $category1 = Category::create(['category_name' => '家電']);
        $category2 = Category::create(['category_name' => 'インテリア']);

        $item->categories()->attach([$category1->id, $category2->id]);

        $response = $this->get('/item/' . $item->id);

        $response->assertStatus(200);
        $response->assertSee('家電');
        $response->assertSee('インテリア');
    }




     /**
     * 大量データでも一覧ページが表示できるかテスト
     */

    public function test_items_index_page_is_displayed_with_large_data()
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