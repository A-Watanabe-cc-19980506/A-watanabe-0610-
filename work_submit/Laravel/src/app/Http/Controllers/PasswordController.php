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
    public function sendResetPasswordMail(ResetInputMailRequest $request)
    {
        try {
            // ユーザー情報取得
            $user = $this->userRepository->findFromMail($request->mail);
            $userToken = $this->userRepository->updateOrCreateUser($user->id);

            // メール送信
            Log::info(__METHOD__ . '...ID:' . $user->id . 'のユーザーにパスワード再設定用メールを送信します。');
            Mail::send(new ResetPasswordMail($user, $userToken));
            Log::info(__METHOD__ . '...ID:' . $user->id . 'のユーザーにパスワード再設定用メールを送信しました。');
        } catch (Exception $e) {
            Log::error(__METHOD__ . '...ユーザーへのパスワード再設定用メール送信に失敗しました。 request_email = ' . $request->mail . ' error_message = ' . $e);
            return redirect()->route('reset.form')
                ->with('flash_message', '処理に失敗しました。時間をおいて再度お試しください。');
        }
        // 不正アクセス防止セッションキー
        session()->put(self::MAIL_SENDED_SESSION_KEY, 'user_reset_password_send_email');

        return redirect()->route('reset.send.complete');
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
            Log::error(__METHOD__ . ' UserTokenの取得に失敗しました。 error_message = ' . $e);
            return redirect()->route('reset.form')
                ->with('flash_message', __('パスワード再設定メールに添付されたURLから遷移してください。'));
        }

        return view('reset.pass_form');
    }

    // パスワード更新
    public function updatePassword(ResetPasswordRequest $request)
    {
        try {
            // ユーザー情報取得
            $userToken = $this->userRepository->getUserTokenFromUser($request->reset_token);
            // パスワード暗号化
            $password = encrypt($request->password);
            $this->userRepository->updateUserPassword($password, $userToken->id);
            Log::info(__METHOD__ . '...ID:' . $userToken->user_id . 'のユーザーのパスワードを更新しました。');
        } catch (Exception $e) {
            Log::error(__METHOD__ . '...ユーザーのパスワードの更新に失敗しました。...error_message = ' . $e);
            return redirect()->route('reset.form')
                ->with('flash_message', __('処理に失敗しました。時間をおいて再度お試しください。'));
        }

        return view('reset.pass_complete');
    }
}

