<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Item;
use App\Models\ItemImage;
use App\Models\User;
use App\Models\Category;

class SampleItemSeeder extends Seeder
{
    
    public function run()
    {
        $seller = User::first();

        $conditionMap = [
            '良好' => 'good',
            '目立った傷や汚れなし' => 'no_visible_damage',
            'やや傷や汚れあり' => 'some_damage',
            '状態が悪い' => 'bad',
        ];

        $items = [
            ['腕時計',15000,'Rolax','スタイリッシュなデザインのメンズ腕時計','item_images/sample_11.jpg','良好', ['ファッション', 'メンズ']],
            ['HDD',5000,'西芝','高速で信頼性の高いハードディスク','item_images/sample_12.jpg','目立った傷や汚れなし', ['家電']],
            ['玉ねぎ3束',300,null,'新鮮な玉ねぎ3束のセット','item_images/sample_13.jpg','やや傷や汚れあり', ['キッチン']],
            ['革靴',4000,null,'クラシックなデザインの革靴','item_images/sample_14.jpg','状態が悪い', ['ファッション', 'メンズ']],
            ['ノートPC',45000,null,'高性能なノートパソコン','item_images/sample_15.jpg','良好', ['家電']],
            ['マイク',8000,null,'高音質のレコーディング用マイク','item_images/sample_16.jpg','目立った傷や汚れなし', ['家電']],
            ['ショルダーバッグ',3500,null,'おしゃれなショルダーバッグ','item_images/sample_17.jpg','やや傷や汚れあり', ['ファッション', 'レディース']],
            ['タンブラー',500,null,'使いやすいタンブラー','item_images/sample_18.jpg','状態が悪い', ['キッチン']],
            ['コーヒーミル',4000,'Starbacks','手動のコーヒーミル','item_images/sample_19.jpg','良好', ['キッチン']],
            ['メイクセット',2500,null,'便利なメイクアップセット','item_images/sample_20.jpg','目立った傷や汚れなし', ['コスメ']],
        ];

        foreach ($items as $data) {

            $item = Item::create([
                'item_name' => $data[0],
                'price' => $data[1],
                'brand' => $data[2],
                'description' => $data[3],
                'seller_id' => $seller->id,
                'condition' => $conditionMap[$data[5]],
                'status' => 'selling',
            ]);

            ItemImage::create([
                'item_id' => $item->id,
                'image_path' => $data[4],
            ]);

            $categoryIds = Category::whereIn('category_name', $data[6])->pluck('id')->toArray();
            $item->categories()->sync($categoryIds);
        }
    }
}
