<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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
    public function imgs()
    {
        // 💡 1つの商品に対して画像は複数（メイン＋サブ3枚など）あるので hasMany になります
        // もしマイグレーション側で「sort_order」や「sort」で並び順を作っているなら、ここでorderByを仕込んでおくと常にメイン画像（0）が先頭に来るので実务でめちゃくちゃ便利です！
        return $this->hasMany(ProductImg::class)->orderBy('sort', 'asc');
    }
    public function category()
    {
        return $this->belongsTo(Category::class)->orderBy('sort', 'asc');
    }

    public function productFavorites(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'product_favorites', 'product_id', 'user_id');
    }
}