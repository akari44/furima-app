<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ExhibitionRequest;

class ItemController extends Controller
{
    //トップページ（商品一覧）の表示
    public function index(){
        return view('index');
    }

    //商品出品画面の表示
    public function createItem(){
        return view('item_create');
    }

     //商品出品画面のバリデーション
    public function storeItem(ExhibitionRequest $request){
        $form = $request -> all();
        return redirect('/sell');


    }
}
