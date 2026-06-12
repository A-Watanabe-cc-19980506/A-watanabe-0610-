<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    // 一括代入を許可するカラムを指定する
    protected $fillable = [
        'user_id',
        'total_price',
        'status',
        'name',
        'zipcode',
        'address',
        'building',
        'stripe_session_id', // 👈 ここにこれを追加してください！
    ];
}