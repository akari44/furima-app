<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use App\Models\User;
use App\Models\Item;
use App\Models\ItemImage;
use App\Models\Like;
use App\Models\Comment;
use App\Models\Category;
use Faker\Factory as Faker;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        $users = User::all();
        $categories = Category::all();

        // 商品50件作成
        $items = Item::factory()
            ->count(50)
            ->make()
            ->each(function ($item) use ($users) {
                $item->seller_id = $users->random()->id;
                $item->save();
            });

        // 画像パス候補（sample_01〜10）
        $imagePaths = collect(range(1, 10))
            ->map(fn($n) => 'item_images/sample_' . str_pad($n, 2, '0', STR_PAD_LEFT) . '.jpg')
            ->all();

        foreach ($items as $item) {

            // 商品画像
            ItemImage::create([
                'item_id' => $item->id,
                'image_path' => Arr::random($imagePaths),
            ]);

             // カテゴリ（1〜3個ランダム）
            $categoryIds = $categories->random(rand(1, 3))->pluck('id')->toArray();
            $item->categories()->sync($categoryIds);

            // いいね（0〜20）
            $likeCount = rand(0, 20);
            $likeUsers = $users->random(min($likeCount, $users->count()));

            foreach ($likeUsers as $user) {
                Like::firstOrCreate([
                    'user_id' => $user->id,
                    'item_id' => $item->id,
                ]);
            }

            // コメント（0〜8）
            $commentCount = rand(0, 8);

            for ($i = 0; $i < $commentCount; $i++) {
                Comment::create([
                    'item_id' => $item->id,
                    'user_id' => $users->random()->id,
                    'body' => $faker->realText(40),
                ]);
            }
        }
    }
}
