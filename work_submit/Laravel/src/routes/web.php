<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\CheckoutController;

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
  Route::post('/send', [PasswordController::class, 'sendResetPasswordMail'])->name('reset.send');
  // メール送信完了
  Route::get('/send/complete', [PasswordController::class, 'sendCompleteResetPasswordMail'])->name('reset.send.complete');
  // パスワード再設定
  Route::get('/password/edit', [PasswordController::class, 'resetPassword'])->name('reset.password.edit');
  // パスワード更新
  Route::post('/password/update', [PasswordController::class, 'updatePassword'])->name('reset.password.update');
  // パスワード再設定用のメール送信フォーム
  Route::get('/', [PasswordController::class, 'requestResetPassword'])->name('reset.form');
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
// 商品詳細
Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');


// ★【追加】「購入に進む」を押したときのログインチェック判定
Route::any('/cart/checkout-check', [CartController::class, 'checkLoginBeforeCheckout'])->name('cart.checkout.check');

// カート画面の表示
Route::resource('/cart', CartController::class);


//確認画面
Route::middleware(['auth'])->group(function () {
  // 💡 A. カート画面から最初に向かう「購入確認画面」を表示するルート
  Route::match(['get', 'post'], '/checkout', [CheckoutController::class, 'index'])->name('checkout.index');

  // 💡 B. 確認画面の「注文を確定する」ボタンからStripe決済ページへリダイレクトするルート
  Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');

  // 💡 C. Stripe決済完了後に戻ってくるルート（提示していただいたコードの動く場所）
  Route::get('/checkout/success', [CheckoutController::class, 'success'])->name('checkout.success');
});

//お気に入り機能
Route::middleware(['auth'])->group(function () {
  //お気に入り追加
  Route::post('/favorites/{product}', [FavoriteController::class, 'store'])->name('favorite.store');
  //お気に入り削除
  Route::delete('/favorites/{product}', [FavoriteController::class, 'destroy'])->name('favorite.destroy');
});