<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Category;
use App\Models\Item;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

//【TestCase15】商品出品画面にて必要な情報が保存できること（カテゴリ、商品の状態、商品名、ブランド名、商品の説明、販売価格）
class ExhibitionTest extends TestCase
{
    use RefreshDatabase; 

    public function test_user_can_create_item_with_required_information()
    {
        Storage::fake('public');

        $user = User::factory()->create([
            'email_verified_at' => now(),
            'postal_code' => '123-4567',
            'address' => '北海道札幌市中央区',
            'building_name' => 'テストマンション101',
        ]);

       
        $category1 = Category::create([
            'category_name' => 'ファッション',
        ]);

        $category2 = Category::create([
            'category_name' => 'メンズ',
        ]);

        // フェイク画像ファイルを作成
        $image = UploadedFile::fake()->create('test.jpg', 100, 'image/jpeg');

        $response = $this->actingAs($user)->post('/sell', [
            'item_name' => 'テスト商品',
            'brand' => 'テストブランド',
            'description' => 'テスト用の商品説明',
            'price' => 9999,
            'condition' => 'good',
            'categories' => json_encode(['ファッション', 'メンズ']),
            'image' => $image,
        ]);

        $response->assertStatus(302);

        $this->assertDatabaseHas('items', [
            'item_name' => 'テスト商品',
            'brand' => 'テストブランド',
            'description' => 'テスト用の商品説明',
            'price' => 9999,
            'condition' => 'good',
            'seller_id' => $user->id,
        ]);

        $item = Item::where('item_name', 'テスト商品')->first();

        // 中間テーブル保存確認
        $this->assertDatabaseHas('category_item', [
            'item_id' => $item->id,
            'category_id' => $category1->id,
        ]);

        $this->assertDatabaseHas('category_item', [
            'item_id' => $item->id,
            'category_id' => $category2->id,
        ]);

        // 画像テーブル保存確認
        $this->assertDatabaseHas('item_images', [
            'item_id' => $item->id,
        ]);

        // storage 保存確認
        Storage::disk('public')->assertExists('item_images/' . $image->hashName());
    }
    
}
