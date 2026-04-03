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

        $item= Item::with('images') -> findOrFail($item_id);

        $paymentMethods = PaymentMethod::where('is_active', true)
        ->orderBy('id')
        ->get();

         // 未購入の下書き purchase を取得
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

    //追加実装stripeCheckout

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

        // 購入確定
        $purchase->update([
            'payment_method_id' => $request->payment_method_id,
        ]);
         // 支払処理分岐①コンビニ払い→この場で購入完了
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
            // Stripe Checkout Session を作成
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

                // purchase と Stripe をひもづける
                'client_reference_id' => (string) $purchase->id,

                // 決済成功後の戻り先
                'success_url' => route('purchase.success', ['item_id' => $item_id]) . '?session_id={CHECKOUT_SESSION_ID}',

                // キャンセル時の戻り先
                'cancel_url' => route('purchase.show', ['item_id' => $item_id]),

                // ユーザーのメールアドレス
                'customer_email' => $user->email,
            ]);

            // Stripe の決済画面へ遷移
            return redirect($session->url);
        }

        // 想定外の支払い方法
        return redirect()->route('purchase.show', ['item_id' => $item_id])
            ->with('error', '選択された支払い方法には対応していません。');
    }

         // Stripe 決済成功後の処理（カード払い用）
    

    public function success(Request $request, $item_id)
    {
        
        if (!$request->session_id) {
            return redirect()->route('items.index')
                ->with('error', '決済情報が確認できませんでした。');
        }

        // Stripe のシークレットキーをセット
        Stripe::setApiKey(config('services.stripe.secret'));

        // Stripe から Checkout Session を取得
        $session = Session::retrieve($request->session_id);

        // 支払い完了しているか確認
        if ($session->payment_status !== 'paid') {
            return redirect()->route('purchase.show', ['item_id' => $item_id])
                ->with('error', '決済が完了していません。');
        }

        $purchaseId = $session->client_reference_id;

        if (!$purchaseId) {
            return redirect()->route('items.index')
                ->with('error', '購入情報の照合に失敗しました。');
        }

        // purchase を取得
        $purchase = Purchase::findOrFail($purchaseId);

        // 本人の purchase かどうか
        
        if ($purchase->buyer_id !== Auth::id()) {
            abort(403);
        }

        // 商品情報の整合性
        $item = Item::findOrFail($purchase->item_id);

        if ((int) $item_id !== (int) $purchase->item_id) {
            return redirect()->route('items.index')
                ->with('error', '商品情報の照合に失敗しました。');
        }

        // 二重更新を防ぐ
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
    public function editAddress($item_id){

        $user = Auth::user();

        $purchase = Purchase::where('buyer_id', $user->id)
            ->where('item_id', $item_id)
            ->whereNull('purchased_at')
            ->firstOrFail();

        return view('address_edit', compact('purchase'));
    }

    //配送先住所変更 保存
    public function updateAddress(ShoppingAddressRequest $request,$item_id){
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
