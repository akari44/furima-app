<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Requests\ShoppingAddressRequest;
use App\Http\Requests\PurchaseRequest;
use App\Models\Item;
use App\Models\PaymentMethod;
use App\Models\Purchase;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class PurchaseController extends Controller
{
    ///商品購入 ページ表示
    public function show($item_id){
        $user = Auth::user();
        $item= Item::with('images')->findOrFail($item_id);
        $paymentMethods = PaymentMethod::where('is_active', true)
        ->orderBy('id')
        ->get();

         // 未購入のpurchase
        $purchase = Purchase::firstOrCreate(
            [
                'buyer_id' => $user->id,
                'item_id' => $item_id,
                'purchased_at' => null
            ],
            [
                'postal_code' => $user->postal_code,
                'address' => $user->address,
                'building_name' => $user->building_name,
            ]
        );
        return view ('purchase',compact('user','item','paymentMethods','purchase'));
    }
    //Stripe Checkoutによる購入処理
    public function store(PurchaseRequest $request, $item_id)
    {
        $item = Item::findOrFail($item_id);
        if ($item->status === 'sold') {
            return redirect()->route('items.index')
            ->with('error', 'この商品は売り切れです。');
        }
        $user = Auth::user();
        $paymentMethod = PaymentMethod::findOrFail($request->payment_method_id);
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

        // 支払処理分岐①コンビニ払い→購入確定
        $purchase->update([
            'payment_method_id' => $request->payment_method_id,
        ]);
         if ($paymentMethod->code === 'konbini') {
            $purchase->update([
                'purchased_at' => now(),
            ]);
            $item->status = 'sold';
            $item->save();
            return redirect()->route('items.index')
                ->with('success', '購入が完了しました。');
        }
         // 支払処理分岐②カード払い→stripe Checkoutに移管
         if ($paymentMethod->code === 'credit_card') {
            Stripe::setApiKey(config('services.stripe.secret'));
            $session = Session::create([
                'mode' => 'payment',
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'jpy',
                        'product_data' => [
                            'name' => $item->item_name,
                        ],
                        'unit_amount' => $item->price,
                    ],
                    'quantity' => 1,
                ]],
                'client_reference_id' => (string) $purchase->id,
                'success_url' => route('purchase.success', ['item_id' => $item_id]) . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('purchase.show', ['item_id' => $item_id]),
                'customer_email' => $user->email,
            ]);
            return redirect($session->url);
        }
        // 想定外の支払い方法
        return redirect()->route('purchase.show', ['item_id' => $item_id])
            ->with('error', '選択された支払い方法には対応していません。');
    }

    // Stripe 決済成功後（カード）
    public function success(Request $request, $item_id)
    {
        if (!$request->session_id) {
            return redirect()->route('items.index')
                ->with('error', '決済情報が確認できませんでした。');
        }
        Stripe::setApiKey(config('services.stripe.secret'));
        $session = Session::retrieve($request->session_id);
        if ($session->payment_status !== 'paid') {
            return redirect()->route('purchase.show', ['item_id' => $item_id])
                ->with('error', '決済が完了していません。');
        }
        $purchaseId = $session->client_reference_id;
        if (!$purchaseId) {
            return redirect()->route('items.index')
                ->with('error', '購入情報の照合に失敗しました。');
        }
        $purchase = Purchase::findOrFail($purchaseId);
        if ($purchase->buyer_id !== Auth::id()) {
            abort(403);
        }
        $item = Item::findOrFail($purchase->item_id);
        if ((int) $item_id !== (int) $purchase->item_id) {
            return redirect()->route('items.index')
                ->with('error', '商品情報の照合に失敗しました。');
        }
        if ($purchase->purchased_at !== null) {
            return redirect()->route('items.index')
                ->with('success', '購入はすでに完了しています。');
        }
        if ($item->status === 'sold') {
            return redirect()->route('items.index')
                ->with('error', 'この商品はすでに売り切れです。');
        }

        // 購入確定
        $purchase->update([
            'purchased_at' => now(),
        ]);
        $item->status = 'sold';
        $item->save();
        return redirect()->route('items.index')
            ->with('success', '購入が完了しました。');
    }

    //配送先住所変更 ページ表示
    public function editAddress($item_id)
    {
        $user = Auth::user();
        $purchase = Purchase::where('buyer_id', $user->id)
            ->where('item_id', $item_id)
            ->whereNull('purchased_at')
            ->firstOrFail();
        return view('address_edit', compact('purchase'));
    }

    //配送先住所変更 保存
    public function updateAddress(ShoppingAddressRequest $request,$item_id)
    {
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
