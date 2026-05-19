<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\RegistrationRequest;
use Illuminate\Support\Facades\Hash;

class RegistrationController extends Controller
{
    /**
     * 入力画面
     */
    public function create(Request $request)
    {
        return view('registration.index', [
            'form' => $request->session()->get('registration', []),
        ]);
    }

    /**
     * 確認画面
     */
    public function show(RegistrationRequest $request)
    {
        $validated = $request->validated();

        $request->session()->put('registration', $validated);

        return view('registration.confirm', [
            'form' => $validated,
        ]);
    }

    /**
     * 完了処理
     */
    public function store(Request $request)
    {
        $form = $request->session()->get('registration');

        if (!$form) {
            return redirect()->route('registration.index');
        }

        // DB保存例
        User::create([
            'last_name_kanji' => $form['last_name_kanji'],
            'first_name_kanji' => $form['first_name_kanji'],
            'last_name_kana' => $form['last_name_kana'],
            'first_name_kana' => $form['first_name_kana'],
            'email' => $form['email'],
            'password' => bcrypt($form['password']),
            'role' => 0, // 一般ユーザー
        ]);

        // 二重送信防止
        $request->session()->forget('registration');

        return view('registration.completed');
    }

}