<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use App\Http\Requests\ShoppingAddressRequest;
use App\Http\Requests\PurchaseRequest;
use App\Models\Item;
use App\Models\PaymentMethod;
use App\Models\Purchase;

class PurchaseController extends Controller
{
    ///商品購入 ページ表示
    public function showPurchaseForm($item_id){
        $user = Auth::user();

        $item= Item::with('images') -> findOrFail($item_id);

        $paymentMethods = PaymentMethod::where('is_active', true)
        ->orderBy('id')
        ->get();

       $purchase = Purchase::firstOrCreate(
        ['buyer_id' => $user->id, 'item_id' => $item_id, 'purchased_at' => null],
        [
            'postal_code' => $user->postal_code,
            'address' => $user->address,
            'building_name' => $user->building_name,
        ]
        );
        return view ('purchase',compact('user','item','paymentMethods','purchase'));
    }

    ///商品購入 支払い情報の仮保存＆確定

    public function storePurchase(PurchaseRequest $request, $item_id)
    {
      
        $item = Item::findOrFail($item_id);

        // すでに売れてたら止める
        if ($item->status === 'sold') {
            return redirect('/')
            ->with('error', 'この商品は売り切れです。');
        }
    
        $user = Auth::user();

        // 下書き purchase を取得（なければ user 住所で作成）
        $purchase = Purchase::firstOrCreate(
        [
            'buyer_id' => $user->id,
            'item_id' => $item_id,
            'purchased_at' => null,
        ],
        [
            'postal_code' => $user->postal_code,
            'address' => $user->address,
            'building_name' => $user->building_name,
        ]
        );

        // 購入確定
        $purchase->update([
            'payment_method_id' => $request->payment_method_id,
            'purchased_at' => now(),
        ]);
        
        // ステータスsold
        $item->status = 'sold';
        $item->save();

        return redirect('/')
            ->with('success', '購入が完了しました。');
    }


    //配送先住所変更 ページ表示
    public function createShoppingAddress($item_id){

        $user = Auth::user();

        $purchase = Purchase::where('buyer_id', $user->id)
            ->where('item_id', $item_id)
            ->whereNull('purchased_at')
            ->firstOrFail();

        return view('address_edit', compact('purchase'));
    }

    //配送先住所変更 バリデーション確認
    public function storeShoppingAddress(ShoppingAddressRequest $request,$item_id){
       $user = Auth::user();

        $purchase = Purchase::where('buyer_id', $user->id)
        ->where('item_id', $item_id)
        ->whereNull('purchased_at')
        ->firstOrFail();

        $purchase->update([
        'postal_code' => $request->postal_code,
        'address' => $request->address,
        'building_name' => $request->building,
        ]);

        return redirect()->route('purchase.show', ['item_id' => $item_id])
        ->with('success', '配送先を更新しました。');
    }
}
