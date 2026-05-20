<?php

namespace App\Repositories\Interfaces;

use App\Models\User;

interface UserRepositoryInterface
{
    /**
     * メールアドレスからユーザー情報を取得
     *
     * @param string $mail
     * @return User
     */
    public function findFromMail(string $mail): User;

    /**
     * パスワードリセット用トークンを発行
     *
     * @param int $userId
     * @return User
     */
    public function updateOrCreateUser(int $userId): User;
    /**
     * トークンからユーザー情報を取得
     * @param string $token
     * @return User
     */
    public function getUserTokenFromUser(string $token): User;

    /**
     * パスワード更新
     *
     * @param string $password
     * @param int $id
     * @return void
     */
    public function updateUserPassword(string $password, int $id): void;
}
