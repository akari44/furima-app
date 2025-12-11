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


/* 商品一覧トップページ表示 */
Route::get('/',[ItemController::class, 'index']);


Route::middleware('auth')->group(function () {
    /* 商品出品ページ表示 */
    Route::get('/sell',[ItemController::class, 'createItem']);

    /* 商品出品ページのバリデーション、DB保存、ページ移動 */
    Route::post('/sell', [ItemController::class, 'storeItem']);
});

/* 商品詳細ページ表示（まだクエリパラメータなし） */
Route::get('/item',[ItemController::class, 'showItemDetail']);

/* 会員登録　ページ表示 */
Route::get('/register', [AuthController::class, 'register']);

/* 会員登録　バリデーション、DB登録、リダイレクト */
Route::post('/register', [AuthController::class, 'storeUser']);

/* ログイン　ページ表示 */
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'loginUser']);

/* プロフィール設定 ページ表示 */
Route::get('/mypage/profile', [ProfileController::class, 'editProfile'])
-> middleware('auth');

/* プロフィール設定のバリデーション、DB保存、ページ移動 */
Route::post('/mypage/profile', [ProfileController::class, 'updateProfile']);

/* プロフィールページ 表示 */
Route::get('/mypage', [ProfileController::class, 'showProfile'])
-> middleware('auth');

/*　商品購入ページ　表示　*/
Route::get('/purchase', [PurchaseController::class,'showPurchaseForm'])
    ->name('purchase.show'); ;

/*　商品購入ページ　購入情報DB保存　移動など　*/
Route::post('/purchase', [PurchaseController::class, 'storePurchase'])
    ->name('purchase.store');

/*　配送先住所の変更ページ　表示*/
Route::get('/purchase/address', [PurchaseController::class,'createShoppingAddress'])
    ->name('address.create');

/*　配送先住所の変更ページ　バリデーション、住所の登録*/
Route::post('/purchase/address', [PurchaseController::class, 'storeShoppingAddress'])
    ->name('address.store');