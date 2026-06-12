<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;
use App\Models\CartItem;

class LoginController extends Controller
{
    //ログイン画面表示
    public function index(Request $request)
    {
        // ログイン画面へ遷移する際、リダイレクト先（例: /cart）を受け取って渡す
        return view('login', ['redirect_to' => $request->query('redirect_to')]);
    }
    //ログイン情報をDBへ送信
    public function login(Request $request)
    {
        // ① バリデーション
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // ② DB検索（email + password）
        $oldSessionId = $request->session()->getId();

        if (Auth::attempt($request->only('email', 'password'))) {
            // Auth::attempt() 内でセッションIDを再生成しているため、
            // ここでは古いセッションIDを保持したまま処理する

            // 管理者は管理画面へ（カート紐付けは不要）
            if (auth()->user()->role === 1) {
                return redirect()->route('admin.index');
            }

            return $this->authenticated($request, auth()->user(), $oldSessionId);
        }

        // ④ 失敗時
        return back()->withErrors([
            'login' => '入力された情報に誤りがあります。',
        ]);
    }
    //ログアウト処理
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        return redirect()->route('user.index');
    }

    protected function authenticated(Request $request, $user, ?string $oldSessionId = null)
    {
        if ($oldSessionId === null) {
            $oldSessionId = $request->input('_old_session_id');
        }

        $currentSessionId = $request->session()->getId();

        // 2. 可能なら2つのセッションIDで検索して、どちらでもマッチするゲストカートを探す
        $guestCartQuery = Cart::query();
        if ($oldSessionId) {
            $guestCartQuery->where('session_key', $oldSessionId);
        }
        if ($currentSessionId) {
            $guestCartQuery->orWhere('session_key', $currentSessionId);
        }
        $guestCart = $guestCartQuery->first();

        if ($guestCart) {
            // 3. ログインユーザー用の本番カートを取得、なければ作成
            $userCart = Cart::firstOrCreate(
                ['user_id' => $user->id],
                ['session_key' => null] // 会員用は session_key を null に
            );

            // 4. ゲストカートの中身（明細）を会員カートへ引っ越し・合算
            foreach ($guestCart->cartItems as $guestItem) {

                // すでに会員カート側に同じバリエーションの商品があるか確認
                $existingUserItem = CartItem::where('cart_id', $userCart->id)
                    ->where('product_variation_id', $guestItem->product_variation_id)
                    ->first();

                if ($existingUserItem) {
                    // すでに同じ商品があれば数量を足し算（合算）
                    $existingUserItem->quantity += $guestItem->quantity;
                    $existingUserItem->save();

                    // 用済みになったゲスト側の明細は削除
                    $guestItem->delete();
                } else {
                    // なければ、親カートのID（cart_id）を会員用のカートIDに書き換えて引っ越し
                    $guestItem->cart_id = $userCart->id;
                    $guestItem->save();
                }
            }

            // 5. 中身が空になった古いゲスト用親カートを削除
            $guestCart->delete();
        }

        // 6. リダイレクト先（/cart）の制御
        if ($request->has('redirect_to')) {
            return redirect($request->input('redirect_to'));
        }

        return redirect()->route('user.index');
    }
}