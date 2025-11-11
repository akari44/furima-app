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

/* 商品一覧トップページ表示 */
Route::get('/',[ItemController::class, 'index']);

/* 商品出品ページ表示 */
Route::get('/sell',[ItemController::class, 'createItem']);

/* 商品出品ページのバリデーション、DB保存、ページ移動 */
Route::post('/sell', [ItemController::class, 'storeItem']);

/* 会員登録　ページ表示 */
Route::get('/register', [AuthController::class, 'register']);

/* 会員情報のバリデーション、DB保存、ページ移動 */
Route::post('/register', [AuthController::class, 'storeUser']);

/* ログイン　ページ表示 */
Route::get('/login', [AuthController::class, 'login']);

/* ログインページのバリデーション、DB検索、ページ移動 */
Route::post('/login', [AuthController::class, 'loginUser']);

/* プロフィール設定 ページ表示 */
Route::get('/mypage/profile', [ProfileController::class, 'editProfile']);

/* プロフィール設定のバリデーション、DB保存、ページ移動 */
Route::post('/mypage/profile', [ProfileController::class, 'updateProfile']);

/* プロフィールページ 表示 */
Route::get('/mypage', [ProfileController::class, 'showProfile']);