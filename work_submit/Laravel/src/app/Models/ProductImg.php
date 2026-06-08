<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductImg extends Model
{
    use HasFactory;

    // 💡 1. テーブル名を明示的に指定（大文字小文字のトラブル防止）
    protected $table = 'product_imgs';

    // 💡 2. 逆方向のリレーション：画像は「1つの商品」に属する
    public function product()
    {
        // 画像(多) に対して 商品は(1) の関係
        return $this->belongsTo(Product::class);
    }
}