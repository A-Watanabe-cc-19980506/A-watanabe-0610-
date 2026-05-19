<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    //ログイン画面表示
    public function index(Request $request)
    {
        return view('login');
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
        if (Auth::attempt($request->only('email', 'password'))) {

            // セッション固定化対策
            $request->session()->regenerate();

            // ③ roleで分岐
            if (auth()->user()->role === 1) {
                // 管理者 → /admin（302）
                return redirect()->route('admin.index');
            }

            // 一般ユーザー → /（302）
            return redirect()->route('user.index');
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

}