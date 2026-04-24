<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Like;
use App\Models\Purchase;


//1000人程度のユーザーを見込んだ場合の見え方・挙動確認テスト

class LargeDataDisplayTest extends TestCase
{
    use RefreshDatabase;
    //商品一覧が大量データ(1000件）でも表示できる
    public function test_items_index_can_be_displayed_with_many_items()
    {
        $loginUser = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $otherUsers = User::factory()->count(50)->create([
            'email_verified_at' => now(),
        ]);

        foreach (range(1, 1000) as $i) {
            Item::factory()->create([
                'seller_id' => $otherUsers->random()->id,
                'item_name' => '大量商品' . $i,
            ]);
        }

        // ログインユーザー自身の商品は除外されるか
        Item::factory()->create([
            'seller_id' => $loginUser->id,
            'item_name' => '自分の商品',
        ]);

        $response = $this->actingAs($loginUser)->get('/');

        $response->assertStatus(200);
        $response->assertSee('大量商品1');
        $response->assertSee('大量商品1000');
        $response->assertDontSee('自分の商品');
    }

    //検索結果が大量データ（1000件）でも正しく表示できる

    public function test_search_result_can_be_displayed_with_many_items()
    {
        $loginUser = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $otherUsers = User::factory()->count(30)->create([
            'email_verified_at' => now(),
        ]);

        foreach (range(1, 1000) as $i) {
            Item::factory()->create([
                'seller_id' => $otherUsers->random()->id,
                'item_name' => '通常商品' . $i,
            ]);
        }

        Item::factory()->create([
            'seller_id' => $otherUsers->random()->id,
            'item_name' => 'アンパン',
        ]);

        Item::factory()->create([
            'seller_id' => $otherUsers->random()->id,
            'item_name' => 'クロワッサン',
        ]);

        Item::factory()->create([
            'seller_id' => $otherUsers->random()->id,
            'item_name' => '塩パン',
        ]);

        $response = $this->actingAs($loginUser)->get('/?keyword=パン');

        $response->assertStatus(200);
        $response->assertSee('アンパン');
        $response->assertSee('塩パン');
        $response->assertDontSee('クロワッサン');
    }

     //マイリスト(いいねした商品）が大量データ（300個）でも表示できる
    public function test_mylist_can_be_displayed_with_many_liked_items()
    {
        $loginUser = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $otherUsers = User::factory()->count(20)->create([
            'email_verified_at' => now(),
        ]);

        $likedItems = collect();

        foreach (range(1, 300) as $i) {
            $item = Item::factory()->create([
                'seller_id' => $otherUsers->random()->id,
                'item_name' => 'いいね商品' . $i,
            ]);

            Like::create([
                'user_id' => $loginUser->id,
                'item_id' => $item->id,
            ]);

            $likedItems->push($item);
        }

        foreach (range(1, 100) as $i) {
            Item::factory()->create([
                'seller_id' => $otherUsers->random()->id,
                'item_name' => '未いいね商品' . $i,
            ]);
        }

        $response = $this->actingAs($loginUser)->get('/?tab=mylist');

        $response->assertStatus(200);
        $response->assertSee('いいね商品1');
        $response->assertSee('いいね商品300');
        $response->assertDontSee('未いいね商品1');
         $response->assertDontSee('未いいね商品100');
    }

    //マイページの出品一覧・購入一覧が大量データでも表示できる
    public function test_mypage_sell_and_buy_lists_can_be_displayed_with_many_items()
    {
        $loginUser = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $otherSeller = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        foreach (range(1, 120) as $i) {
            Item::factory()->create([
                'seller_id' => $loginUser->id,
                'item_name' => '出品商品' . $i,
            ]);
        }

        foreach (range(1, 80) as $i) {
            $item = Item::factory()->create([
                'seller_id' => $otherSeller->id,
                'item_name' => '購入商品' . $i,
                'status' => 'sold',
            ]);

            Purchase::create([
                'buyer_id' => $loginUser->id,
                'item_id' => $item->id,
                'postal_code' => '123-4567',
                'address' => '北海道札幌市中央区',
                'building_name' => 'テストマンション101',
                'purchased_at' => now(),
            ]);
        }

        $sellResponse = $this->actingAs($loginUser)->get('/mypage?page=sell');
        $sellResponse->assertStatus(200);
        $sellResponse->assertSee('出品商品1');
        $sellResponse->assertSee('出品商品120');

        $buyResponse = $this->actingAs($loginUser)->get('/mypage?page=buy');
        $buyResponse->assertStatus(200);
        $buyResponse->assertSee('購入商品1');
        $buyResponse->assertSee('購入商品80');
    }
}
