<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;

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

// 管理画面
// Route::middleware(['admin', 'auth'])->group(function () {
//   Route::group(['prefix' => '/admin', 'as' => 'admin.'], function () {
//     // 管理画面トップ
//     Route::get('/', [AdminController::class, 'index'])->name('index');
//     // 商品登録画面
//     Route::get('/product/add', [AdminProductController::class, 'add'])->name('product.add');
//   });
// });

//トップページの表示
Route::get('/', [UserController::class, 'index'])->name('user.index');
//ログインページ表示
Route::get('/login', [LoginController::class, 'index'])->name('login');
//送信機能
Route::post('/login', [LoginController::class, 'login'])
  ->name('post.login');

//パスワード再設定処理
Route::prefix('reset')->group(function () {
  // メール送信処理
  Route::post('/send', 'PasswordController@sendResetPasswordMail')->name('reset.send');
  // メール送信完了
  Route::get('/send/complete', 'PasswordController@sendCompleteResetPasswordMail')->name('reset.send.complete');
  // パスワード再設定
  Route::get('/password/edit', 'PasswordController@resetPassword')->name('reset.password.edit');
  // パスワード更新
  Route::post('/password/update', 'PasswordController@updatePassword')->name('reset.password.update');
  // パスワード再設定用のメール送信フォーム
  Route::get('/', 'PasswordController@requestResetPassword')->name('reset.form');
});

// 会員登録入力画面
Route::get('/registration/index', [RegistrationController::class, 'create'])
  ->name('registration.index');
// 登録情報確認画面
Route::post('/registration/confirm', [RegistrationController::class, 'show'])
  ->name('registration.confirm');
// 登録完了画面
Route::post('/registration/completed', [RegistrationController::class, 'store'])
  ->name('registration.completed');
//ログアウト処理
Route::post('/logout', [LoginController::class, 'logout'])
  ->name('logout');

//　商品一覧
Route::get('/products', [ProductController::class, 'index'])->name('products.index');