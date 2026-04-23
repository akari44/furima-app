<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Item;
use App\Models\Purchase;
use App\Models\User;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    //【TestCase13】必要な情報が取得できる（プロフィール画像、ユーザー名、出品した商品一覧、購入した商品一覧）
    public function test_profile_page_displays_user_information(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
            'name' => 'テスト太郎',
            'avatar_path' => 'avatars/avatar_01.jpg',
            'postal_code' => '111-1111',
            'address' => '北海道札幌市厚別区',
            'building_name' => 'テストマンション101',
        ]);

        $seller = User::factory()->create();

        // 出品した商品
        $sellingItem = Item::factory()->create([
            'seller_id' => $user->id,
            'item_name' => '出品商品A',
        ]);

        // 購入した商品
        $boughtItem = Item::factory()->create([
            'seller_id' => $seller->id,
            'item_name' => '購入商品B',
            'status' => 'sold',
        ]);

        Purchase::create([
            'buyer_id' => $user->id,
            'item_id' => $boughtItem->id,
            'postal_code' => '111-1111',
            'address' => '北海道札幌市厚別区',
            'building_name' => 'テストマンション101',
            'purchased_at' => now(),
        ]);

        // 出品一覧（sell）
        $response = $this->actingAs($user)->get('/mypage');

        $response->assertStatus(200);
        $response->assertSee('テスト太郎');
        $response->assertSee('avatars/avatar_01.jpg');
        $response->assertSee('出品商品A');

        // 購入一覧（buy）
        $buyResponse = $this->actingAs($user)->get('/mypage?page=buy');

        $buyResponse->assertStatus(200);
        $buyResponse->assertSee('購入商品B');
    }

    //【TestCase14】変更項目が初期値として過去設定されていること（プロフィール画像、ユーザー名、郵便番号、住所）
    public function test_profile_edit_page_displays_initial_values(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
            'name' => '初期値タロウ',
            'avatar_path' => 'avatars/avatar_02.jpg',
            'postal_code' => '222-2222',
            'address' => '北海道札幌市中央区',
            'building_name' => 'サンプルビル202',
        ]);

        $response = $this->actingAs($user)->get('/mypage/profile');

        $response->assertStatus(200);
        $response->assertSee('初期値タロウ');
        $response->assertSee('avatars/avatar_02.jpg');
        $response->assertSee('222-2222');
        $response->assertSee('北海道札幌市中央区');
    }
}
