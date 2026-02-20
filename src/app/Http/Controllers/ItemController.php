<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Requests\ExhibitionRequest;
use App\Models\Item;
use App\Models\Category;
use App\Models\ItemImage;
use App\Models\Like;

class ItemController extends Controller
{
    //トップページ（商品一覧）の表示
    public function index(Request $request){

       $userId  = auth()->id();
        $tab     = $request->query('tab', 'all');
        $keyword = $request->query('keyword');

        if (!$userId && $tab === 'mylist') {
            $items = collect();
            return view('index', compact('items', 'tab', 'keyword'));
        }

        if ($tab === 'mylist') {
            $items = Item::with('images')
                ->whereHas('likes', function ($q) use ($userId) {
                    $q->where('user_id', $userId);
                })
                ->keyword($keyword)     // あれば検索
                ->latest()
                ->get();

        } else {
            $items = Item::with('images')
                ->when($userId, function ($q) use ($userId) {
                    $q->where('seller_id', '!=', $userId);
                })
                ->keyword($keyword)
                ->latest()
                ->get();
        }

        return view('index', compact('items', 'tab', 'keyword'));
    }

    //商品詳細ページの表示
    public function showItemDetail($item_id)
    {
         $item = Item::with([
            'categories',
            'images',
            'seller', 
            'comments' => function ($q) {
                $q->latest(); // created_at desc
            },
            'comments.user',
        ])
        ->withCount('likes')
        ->withCount('comments') 
        ->findOrFail($item_id);

        $isLiked = auth()->check()
            ? auth()->user()->likedItems()->where('item_id', $item->id)->exists()
            : false;

        return view('item_detail', compact('item', 'isLiked'));
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
            
            //画像
            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('item_images', 'public');

                ItemImage::create([
                    'item_id'    => $item->id,
                    'image_path' => $path,
                ]);
            }

            //カテゴリ
            $names = json_decode($request->input('categories', '[]'), true);
            $names = is_array($names) ? $names : [];

            $categoryIds = Category::whereIn('category_name', $names)->pluck('id')->toArray();

            $item->categories()->sync($categoryIds);

        });

        return redirect('/')->with('message', '商品を登録しました！');
    }
}
