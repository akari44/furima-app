<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    ///商品購入 ページ表示
    public function showPurchaseForm(){
        return view ('/purchase');
    }
}
