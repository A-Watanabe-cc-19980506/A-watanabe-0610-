<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\CartItem;

class Cart extends Model
{
    use HasFactory;

    // 複数代入（Mass Assignment）を許可するカラムを指定
    protected $fillable = [
        'user_id',
        'session_key',
    ];

    /**
     * カート明細（子テーブル）とのリレーション
     * 1つのカートは、複数の明細（cart_items）を持ちます
     */
    public function cartItems()
    {
        return $this->hasMany(CartItem::class, 'cart_id', 'id');
    }

    /**
     * ユーザー（Userモデル）とのリレーション（任意）
     * ログインユーザーの情報をカートから直接引きたい場合に使用します
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}