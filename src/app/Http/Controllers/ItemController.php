<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Requests\ExhibitionRequest;
use App\Models\Item;
use App\Models\ItemImage;

class ItemController extends Controller
{
    //トップページ（商品一覧）の表示
    public function index(){

        $userId = auth()->id(); // ログインしていなければ null

        $items = Item::when($userId, function ($query, $userId) {
        return $query->where('seller_id', '!=', $userId);
        })
        ->latest()
        ->get();

        return view('index', compact('items'));
    }

    //商品詳細ページの表示
    public function showItemDetail(){
        return view('item_detail');
    }


    //商品出品画面の表示
    public function createItem(){
        return view('item_create');
    }

     //商品出品画面  バリデーション、商品情報DB保存、商品画像のDB保存
    public function storeItem(ExhibitionRequest $request){
        DB::transaction(function () use ($request) {
            
            $item =Item::create([
            'item_name'   => $request->item_name,
            'price'       => $request->price,
            'description' => $request->description,
            'seller_id'   => auth()->id(),
            ]);

            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('item_images', 'public');

                ItemImage::create([
                    'item_id'    => $item->id,
                    'image_path' => $path,
                ]);
            }

        });

        return redirect('/')->with('message', '商品を登録しました！');
    }
}
