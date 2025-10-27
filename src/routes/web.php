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
use App\Http\Controllers\LoginController;

/* 会員登録　ページ表示 */
Route::get('/register', [AuthController::class, 'register']);

/* 会員情報のバリデーション、DB保存、ページ移動 */
Route::post('/register', [AuthController::class, 'storeUser']);

/* ログイン　ページ表示 */
Route::get('/login', [AuthController::class, 'login']);

/* ログインページのバリデーション、DB検索、ページ移動 */
Route::post('/login', [AuthController::class, 'loginUser']);

