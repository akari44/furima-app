<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\CommentController;


/* 商品一覧トップページ表示 */
Route::get('/',[ItemController::class, 'index'])
 ->name('items.index');

/* 会員登録　ページ表示 */
Route::get('/register', [AuthController::class, 'register'])
->name('register');

/* 会員登録　バリデーション、DB登録、リダイレクト */
Route::post('/register', [AuthController::class, 'storeUser'])
->name('register.store');

/* ログイン　ページ表示 */
Route::get('/login', [AuthController::class, 'login'])->name('login');

/* ログイン　会員情報照合、商品一覧へ */
Route::post('/login', [AuthController::class, 'loginUser'])
->name('login.store');

/* 商品詳細ページ表示 */
Route::get('/item/{item_id}',[ItemController::class, 'show'])
->name('items.show');

Route::middleware('auth')->group(function () {
    /* 商品詳細ページ コメント送信 */
    Route::post('/item/{item_id}/comments', [CommentController::class, 'storeComments'])
    ->name('comments.store');

    /*　商品購入ページ　表示　*/
    Route::get('/purchase/{item_id}', [PurchaseController::class,'show'])
        ->name('purchase.show'); 

    /*　商品購入ページ　購入情報DB保存　移動など　*/
    Route::post('/purchase/{item_id}', [PurchaseController::class, 'store'])
        ->name('purchase.store');

    /*　配送先住所の変更ページ　表示*/
    Route::get('/purchase/address/{item_id}', [PurchaseController::class,'editAddress'])
        ->name('purchase.address.edit');

    /*　配送先住所の変更ページ　バリデーション、住所の登録*/
    Route::post('/purchase/address/{item_id}', [PurchaseController::class, 'updateAddress'])
        ->name('purchase.address.update');
        
    /* 商品出品ページ表示 */
    Route::get('/sell',[ItemController::class, 'createItem'])
    ->name('items.create');

    /* 商品出品ページのバリデーション、DB保存、ページ移動 */
    Route::post('/sell', [ItemController::class, 'storeItem'])
    ->name('items.store');

     /* プロフィールページ 表示 */
    Route::get('/mypage', [ProfileController::class, 'showProfile'])
        ->name('profile.show');

   /* プロフィール設定 ページ表示 */
    Route::get('/mypage/profile', [ProfileController::class, 'editProfile'])
        ->name('profile.edit');

    /* プロフィール設定のバリデーション、DB保存 */
    Route::post('/mypage/profile', [ProfileController::class, 'updateProfile'])
        ->name('profile.update');
    
});


/* いいねボタンのトグル */
Route::post('/items/{item}/like', [LikeController::class, 'toggle'])
    ->middleware('auth')
    ->name('items.like');
