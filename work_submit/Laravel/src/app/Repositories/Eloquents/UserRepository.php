<?php

namespace App\Repositories\Eloquents;

use App\Models\User;
use App\Models\UserToken;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class UserRepository implements UserRepositoryInterface
{
    private $user;
    private $userToken;

    /**
     * constructor
     *
     * @param User $user
     */
    public function __construct(User $user, UserToken $userToken)
    {
        $this->user = $user;
        $this->userToken = $userToken;
    }

    // メールアドレスからユーザー情報取得
    public function findFromMail(string $email): User
    {
        return $this->user->where('email', $email)->firstOrFail();
    }

    // パスワードリセット用トークンを発行
    public function updateOrCreateUser($userId): UserToken
    {
        // パスワードリセット用トークンを生成
        $accessKey = Str::random(64);
        $expireDate = now()->addHours(24);

        // updateOrCreate（既存にあれば更新、なければ新規作成）
        return UserToken::updateOrCreate(
            ['user_id' => $userId],
            [
                'rest_password_access_key' => $accessKey,
                'rest_password_expire_data' => $expireDate,
            ]
        );
    }
    // トークンからユーザートークン情報を取得
    public function getUserTokenFromUser(string $token): UserToken
    {
        return $this->userToken->where('rest_password_access_key', $token)->firstOrFail();
    }

    // パスワード更新
    public function updateUserPassword(string $password, int $id): void
    {
        $this->user->where('id', $id)->update(['password' => $password]);
    }
}
