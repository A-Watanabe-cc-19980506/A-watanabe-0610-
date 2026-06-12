<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;

    // テーブル名が自動推測（cart_details）と異なる場合は明示的に指定してください
    // protected $table = 'cart_details';
    public $timestamps = true; // 念のため明示的にONにする
    protected $fillable = [
        'cart_id',
        'product_variation_id', // ※テーブルのカラム物理名が大文字小文字（product_Variation_id）の場合は正確に合わせてください
        'quantity',
        'is_checked',
        // 'created_at', // ★ もしこれでも直らない場合はここを追加
        // 'updated_at',
    ];

    /**
     * 親カート（Cartモデル）とのリレーション
     * 明細は特定の1つのカートに属しています
     */
    public function cart()
    {
        return $this->belongsTo(Cart::class, 'cart_id', 'id');
    }

    /**
     * 商品バリエーション（ProductVariationモデル）とのリレーション
     * 明細の1行は、特定の色・サイズが選ばれた1つのバリエーション情報を持っています
     */
    public function productVariation()
    {
        // ※ProductVariationのモデル名は実際の名称（ProductVarなど）に合わせてください
        return $this->belongsTo(ProductVariation::class, 'product_variation_id', 'id');
    }
    public function product()
    {
        // HasOneThrough（~を経由した一対一）を使い、
        // ProductVariation（バリエーションテーブル）を経由して Product（商品テーブル）を取得します
        return $this->hasOneThrough(
            Product::class,            
            ProductVariation::class,   
            'id',                      
            'id',                      
            'product_variation_id',    
            'product_id'              
        );
    }
}