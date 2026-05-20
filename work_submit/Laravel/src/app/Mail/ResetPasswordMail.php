<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use Carbon\Carbon;

class ResetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    private $user;
    private $userToken;

    /**
     * construct
     *
     * @param User $user
     * @param User $userToken
     */
    public function __construct(User $user, User $userToken)
    {
        $this->user = $user;
        $this->userToken = $userToken;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // トークン取得
        $tokenParam = ['reset_token' => $this->userToken->rest_password_access_key];
        $now = Carbon::now();

        // 署名付き有効期限24時間のURLを生成
        $url = URL::temporarySignedRoute('reset.password.edit' , $now->addHours(24), $tokenParam);

        // HTML形式でメール作成
        return $this->view('users.password_reset_mail')
                    ->subject('パスワード再設定用URLのご案内')
                    ->from(config('mail.from.address'), config('mail.from.last_name_kana'), config('mail.from.first_name_kana'))
                    ->to($this->user->mail)
                    ->with([
                        'user' => $this->user,
                        'url' => $url,
                        ]);
    }
}
