<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Http\Requests\ResetInputMailRequest;
use App\Mail\ResetPasswordMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Exception;
use App\Http\Requests\ResetPasswordRequest;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class PasswordController extends Controller
{
    private $userRepository;
    private const MAIL_SENDED_SESSION_KEY = 'user_reset_password_mail_sended_action';

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }
    /**
     * パスワードリセット
     */

    // パスワード再設定用のメール送信フォーム
    public function requestResetPassword()
    {
        return view('reset.mail_form');
    }

    //  メール送信
    // メール送信
    public function sendResetPasswordMail(ResetInputMailRequest $request)
    {
        // 1. 入力されたメールアドレスのバリデーション
        $request->validate(['email' => 'required|email']);

        try {
            // 2. リポジトリを使って、メールアドレスからユーザーを検索（なければ自動でエラーを投げてくれます）
            $user = $this->userRepository->findFromMail($request->email);

            // 3. 🔥ここが超重要！リポジトリの関数を使って、DBに正しいトークンを保存・発行する
            // これにより、DBの「rest_password_access_key」にトークンが保存されます。
            $userToken = $this->userRepository->updateOrCreateUser($user->id);
            // 4. 正しくDBに保存されたトークン（$userTokenオブジェクト）をメールクラスに引き渡す
            $mailable = new ResetPasswordMail($user, $userToken);
            Mail::to($user->email)->send($mailable);

            // 送信完了画面へのリダイレクト
            return view('reset.mail_complete');

        } catch (\Exception $e) {
            // 念のためエラーが起きた場合はログに残すか画面に出す
            Log::error('メール送信エラー: ' . $e->getMessage());
            return back()->withErrors(['email' => '処理中にエラーが発生しました。']);
        }
    }
    // メール送信完了
    public function sendCompleteResetPasswordMail()
    {
        // 不正アクセス防止セッションキーを持っていない場合
        if (session()->pull(self::MAIL_SENDED_SESSION_KEY) !== 'user_reset_password_send_email') {
            return redirect()->route('reset.form')
                ->with('flash_message', '不正なリクエストです。');
        }
        return view('reset.mail_complete');
    }

    // パスワード再設定
    public function resetPassword(Request $request)
    {
        // 署名付きURLではない場合
        if (!$request->hasValidSignature()) {
            abort(403, 'URLの有効期限が過ぎたためエラーが発生しました。パスワード再設定メールを再発行してください。');
        }

        $resetToken = $request->reset_token;

        try {
            // ユーザー情報取得
            $userToken = $this->userRepository->getUserTokenFromUser($resetToken);
        } catch (Exception $e) {
            dd('DBからトークンが見つかりませんでした。エラー内容:', $e->getMessage());
            Log::error(__METHOD__ . ' UserTokenの取得に失敗しました。 error_message = ' . $e);
            return redirect()->route('reset.form')
                ->with('flash_message', __('パスワード再設定メールに添付されたURLから遷移してください。'));
        }

        return view('reset.pass_form', compact('userToken'));
    }

    // パスワード更新
    public function updatePassword(ResetPasswordRequest $request)
    {
        try {
            // ユーザー情報取得
            $userToken = $this->userRepository->getUserTokenFromUser($request->reset_token);
            // パスワードハッシュ化
            $password = Hash::make($request->password);
            $this->userRepository->updateUserPassword($password, $userToken->user_id);
            Log::info(__METHOD__ . '...ID:' . $userToken->user_id . 'のユーザーのパスワードを更新しました。');
        } catch (Exception $e) {
            Log::error(__METHOD__ . '...ユーザーのパスワードの更新に失敗しました。...error_message = ' . $e);
            return redirect()->route('reset.form')
                ->with('flash_message', __('処理に失敗しました。時間をおいて再度お試しください。'));
        }

        return view('reset.pass_complete');
    }
}

