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
    public function index(){

        $userId = auth()->id(); // ログインしていなければ null

        $tab = request('tab', 'all');

       // 未ログイン
        if (!$userId && $tab === 'mylist') {
            $items = collect();
         return view('index', compact('items', 'tab'));
            }

        // マイリストtab
        if ($tab === 'mylist') {
            $items = Like::where('user_id', $userId)
                ->with(['item.images'])
                ->latest()
                ->get()
                ->pluck('item')
                ->filter(); // null除去
        } else {
        // 全商品（自分の出品は除外）
        $items = Item::with('images')
            ->when($userId, function ($query, $userId) {
                return $query->where('seller_id', '!=', $userId);
            })
            ->latest()
            ->get();
        }

        return view('index', compact('items', 'tab'));
    }

    //商品詳細ページの表示
    public function showItemDetail($item_id)
    {
        $item = Item::with('categories', 'images')
            ->withCount('likes') // ★ likes_count が付く
            ->findOrFail($item_id);

        // ★ 自分がこの商品をいいね済みか
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
