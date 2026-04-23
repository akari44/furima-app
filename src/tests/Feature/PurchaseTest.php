<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Item;
use App\Models\PaymentMethod;
use App\Models\Purchase;
use App\Models\User;

class PurchaseTest extends TestCase
{
   use RefreshDatabase;

   //【TestCase10-1】「購入する」ボタンを押下すると購入が完了する

    public function test_user_can_purchase_item()
    {
        $buyer = User::factory()->create([
            'postal_code' => '123-4567',
            'address' => '北海道札幌市手稲区',
            'building_name' => 'サッポロマンション101',
        ]);

        $seller = User::factory()->create();

        $item = Item::factory()->create([
            'seller_id' => $seller->id,
            'status' => 'selling',
        ]);

        $paymentMethod = PaymentMethod::create([
            'display_name' => 'カード払い',
            'code' => 'credit_card',
            'stripe_method' => 'card',
            'is_active' => true,
        ]);

        $response = $this->actingAs($buyer)->post('/purchase/' . $item->id, [
            'payment_method_id' => $paymentMethod->id,
        ]);

        $response->assertStatus(302);

        $this->assertDatabaseHas('purchases', [
            'buyer_id' => $buyer->id,
            'item_id' => $item->id,
        ]);
    }

    //【TestCase10-2】 購入した商品は商品一覧画面にて「sold」と表示される
    public function test_purchased_item_is_displayed_as_sold_on_index_page()
    {
        $buyer = User::factory()->create([
            'postal_code' => '123-4567',
            'address' => '北海道札幌市手稲区',
            'building_name' => 'サッポロマンション101',
        ]);

        $seller = User::factory()->create();

        $item = Item::factory()->create([
            'seller_id' => $seller->id,
            'item_name' => '購入済み商品',
            'status' => 'sold',
        ]);

        $response = $this->actingAs($buyer)->get('/');

        $response->assertStatus(200);
        $response->assertSee('購入済み商品');
        $response->assertSee('Sold');
    }

    //【TestCase10-3】「プロフィール/購入した商品一覧」に追加されている

    public function test_purchased_item_is_displayed_on_profile_purchase_list()
    {
        $buyer = User::factory()->create([
        'email_verified_at' => now(),
        'postal_code' => '123-4567',
        'address' => '北海道札幌市手稲区',
        'building_name' => 'サッポロマンション101',
        ]);
        $seller = User::factory()->create();
        $item = Item::factory()->create([
            'seller_id' => $seller->id,
            'item_name' => 'テスト商品',
            'status' => 'sold',
        ]);

        Purchase::create([
            'buyer_id' => $buyer->id,
            'item_id' => $item->id,
            'postal_code' => '123-4567',
            'address' => '北海道札幌市手稲区',
            'building_name' => 'サッポロマンション101',
            'purchased_at' => now(),
        ]);

        $response = $this->actingAs($buyer)->get('/mypage?page=buy');

        $response->assertStatus(200);
        $response->assertSee('テスト商品');
    }

    // 【TestCase11-1】小計画面で変更が反映される(画面の確認不可のため保存されるテスト)

    public function test_selected_payment_method_is_reflected_in_purchase(): void
    {
        $buyer = User::factory()->create([
            'postal_code' => '123-4567',
            'address' => '北海道札幌市手稲区',
            'building_name' => 'サッポロマンション101',
        ]);

        $seller = User::factory()->create();

        $item = Item::factory()->create([
            'seller_id' => $seller->id,
            'status' => 'selling',
        ]);

        $paymentMethod = PaymentMethod::create([
            'display_name' => 'コンビニ払い',
            'code' => 'konbini',
            'stripe_method' => 'konbini',
            'is_active' => true,
        ]);

        $response = $this->actingAs($buyer)->post('/purchase/' . $item->id, [
            'payment_method_id' => $paymentMethod->id,
        ]);

        $response->assertStatus(302);

        $this->assertDatabaseHas('purchases', [
            'buyer_id' => $buyer->id,
            'item_id' => $item->id,
            'payment_method_id' => $paymentMethod->id,
        ]);
    }

     //【TestCase12-1】送付先住所変更画面にて登録した住所が商品購入画面に反映されている

     public function test_changed_address_is_reflected_on_purchase_page(): void
    {
        $buyer = User::factory()->create([
            'email_verified_at' => now(),
            'postal_code' => '111-1111',
            'address' => '旧住所',
            'building_name' => '旧建物',
        ]);

        $seller = User::factory()->create();

        $item = Item::factory()->create([
            'seller_id' => $seller->id,
            'status' => 'selling',
        ]);

        // 未購入 purchase レコードを先に作る
        Purchase::create([
            'buyer_id' => $buyer->id,
            'item_id' => $item->id,
            'postal_code' => $buyer->postal_code,
            'address' => $buyer->address,
            'building_name' => $buyer->building_name,
            'purchased_at' => null,
        ]);

        $this->actingAs($buyer)->post('/purchase/address/' . $item->id, [
            'postal_code' => '999-9999',
            'address' => '新住所',
            'building' => '新建物',
        ]);

        $response = $this->actingAs($buyer)->get('/purchase/' . $item->id);

        $response->assertStatus(200);
        $response->assertSee('999-9999');
        $response->assertSee('新住所');
        $response->assertSee('新建物');
    }

     //【TestCase12-2】購入した商品に送付先住所が紐づいて登録される
     
    public function test_purchase_is_saved_with_changed_shipping_address(): void
    {
        $buyer = User::factory()->create([
            'email_verified_at' => now(),
            'postal_code' => '111-1111',
            'address' => '旧住所',
            'building_name' => '旧建物',
        ]);

        $seller = User::factory()->create();

        $item = Item::factory()->create([
            'seller_id' => $seller->id,
            'status' => 'selling',
        ]);

        $paymentMethod = PaymentMethod::create([
            'display_name' => 'カード払い',
            'code' => 'credit_card',
            'stripe_method' => 'card',
            'is_active' => true,
        ]);

        // 未購入 purchase レコードを先に作る
        Purchase::create([
            'buyer_id' => $buyer->id,
            'item_id' => $item->id,
            'postal_code' => $buyer->postal_code,
            'address' => $buyer->address,
            'building_name' => $buyer->building_name,
            'payment_method_id' => null,
            'purchased_at' => null,
        ]);

        // 住所変更
        $this->actingAs($buyer)->post('/purchase/address/' . $item->id, [
            'postal_code' => '888-8888',
            'address' => '購入用住所',
            'building' => '購入用建物',
        ]);

        // 購入実行
        $this->actingAs($buyer)->post('/purchase/' . $item->id, [
            'payment_method_id' => $paymentMethod->id,
        ]);

        $this->assertDatabaseHas('purchases', [
            'buyer_id' => $buyer->id,
            'item_id' => $item->id,
            'postal_code' => '888-8888',
            'address' => '購入用住所',
            'building_name' => '購入用建物',
            'payment_method_id' => $paymentMethod->id,
        ]);
    }
}

