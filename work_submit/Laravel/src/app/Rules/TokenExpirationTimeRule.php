<?php

namespace App\Rules;

use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Contracts\Validation\Rule;
use Carbon\Carbon;


class TokenExpirationTimeCheck implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * トークンの有効期限をチェック
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $now = Carbon::now();
        $userRepository = app()->make(UserRepositoryInterface::class);
        $userToken = $userRepository->getUserTokenFromUser($value);
        $expireTime = new Carbon($userToken->rest_password_expire_data);

        return $now->lte($expireTime);
    }

    /**
     * エラーメッセージ
     *
     * @return string
     */
    public function message()
    {
        return '有効期限が過ぎています。パスワード再設定用のメールを再発行してください。';
    }
}

