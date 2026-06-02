<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\builder;

class Product extends Model
{
    use HasFactory;

    // 一括画面から保存を許可するカラム（前の手順で決めた親テーブルの項目）
    protected $fillable = [
        'name',
        'price',
        'img_path',
        'description',
        'category_id',
    ];

    /**
     * リレーション定義：商品バリエーション（子）への紐付け
     * 1つの商品は、複数のバリエーションを持つ（1対多）
     */
    public function variants()
    {
        return $this->hasMany(ProductVariation::class);
    }
    // スコープの定義（メソッド名はキャメルケースで scopeOrderByDirection などにするのがお作法ですが、元の名前に合わせるなら scopeOrder）
    public function scopeOrder($query, $select)
    {
        if ($select === 'asc') {
            return $query->orderBy('created_at', 'asc');
        } elseif ($select === 'desc') {
            return $query->orderBy('created_at', 'desc');
        }

        // 引数が不正、または空の場合はデフォルトの全件取得（並び替えなし）
        return $query;
    }
}
