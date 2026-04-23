<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Item;
use App\Models\User;

class LikeTest extends TestCase
{
    use RefreshDatabase;

    //【テスト8-1】いいねアイコンを押下することによって、いいねした商品として登録することができる
     public function test_user_can_like_an_item()
    {
        $user = User::factory()->create();
        $seller = User::factory()->create();

        $item = Item::factory()->create([
            'seller_id' => $seller->id,
        ]);

        $response = $this->actingAs($user)->post('/items/' . $item->id . '/like');
        $response->assertStatus(200);

        $response->assertJson([
            'liked' => true,
            'count' => 1,
        ]);
        $this->assertDatabaseHas('likes', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);
    }

     //【テスト8-2】いいね後に状態が変わる（JSONで確認）
    public function test_like_response_changes_state()
    {
        $user = User::factory()->create();
        $seller = User::factory()->create();
        $item = Item::factory()->create([
            'seller_id' => $seller->id,
        ]);

        $response = $this->actingAs($user)
            ->post('/items/' . $item->id . '/like');
        $response->assertStatus(200);

        $response->assertJson([
            'liked' => true,
            'count' => 1,
        ]);
    }
            
    //【テスト8-3】再度いいねアイコンを押下することによって、いいねを解除することができる
    public function test_user_can_unlike_an_item()
    {
        $user = User::factory()->create();
        $seller = User::factory()->create();
        $item = Item::factory()->create([
            'seller_id' => $seller->id,
        ]);

        $user->likedItems()->attach($item->id);
        $response = $this->actingAs($user)->post('/items/' . $item->id . '/like');
        $response->assertStatus(200);

        $response->assertJson([
            'liked' => false,
            'count' => 0,
        ]);

        $this->assertDatabaseMissing('likes', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);
    }
    
    

}
