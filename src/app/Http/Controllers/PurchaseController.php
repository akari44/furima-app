<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ShoppingAddressRequest;

class PurchaseController extends Controller
{
    ///商品購入 ページ表示
    public function showPurchaseForm(){
        return view ('purchase');
    }

    //配送先住所変更 ページ表示
    public function createShoppingAddress(){
        return view ('address_edit');
    }

    //配送先住所変更 バリデーション確認
    public function storeShoppingAddress(ShoppingAddressRequest $request){
        $form = $request -> all();
        return redirect('address_edit');

    }
}
