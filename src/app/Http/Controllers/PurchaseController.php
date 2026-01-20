<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use App\Http\Requests\ShoppingAddressRequest;
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

    ///商品購入 支払い情報の保存

    public function storePurchase(Request $request, $item_id)
    {
        $user = Auth::user();

        $request->validate([
            'payment_method_id' => ['required', 'exists:payment_methods,id'],
        ]);

        // 二重購入防止
        if (Purchase::where('item_id', $item_id)->exists()) {
            return redirect()
                ->route('purchase.show', ['item_id' => $item_id])
                ->with('error', 'この商品はすでに購入されています。');
        }

        Purchase::updateOrCreate(
            ['buyer_id' => $user->id, 'item_id' => $item_id],
            [
                'payment_method_id' => $request->payment_method_id,
                'purchased_at' => now(), 
            ]
        );

        return redirect()->route('purchase.show', ['item_id' => $item_id])
            ->with('success', '支払い方法を保存しました。');
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
    public function storeShoppingAddress(ShoppingAddressRequest $request){
        $form = $request -> all();
        return redirect('address_edit');

    }
}
