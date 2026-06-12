<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariation extends Model
{
    use HasFactory;

    // 一括画面から保存を許可するカラム（子テーブルの項目）
    protected $fillable = [
        'product_id',
        'color',
        'size',
        'stock',
    ];

    /**
     * リレーション定義：商品（親）への紐付け
     * このバリエーションは、特定の商品に属する
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function color()
    {
        return $this->belongsTo(Color::class);
    }
    public function size()
    {
        return $this->belongsTo(Size::class);
    }
}