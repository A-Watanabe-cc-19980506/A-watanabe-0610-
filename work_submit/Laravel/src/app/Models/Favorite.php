<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    use HasFactory;
    // 👇 この1行を追加（または修正）します
    protected $table = 'product_favorites';
    protected $fillable = [
        'user_id',
        'product_id',
    ];
    //userモデルとのリレーション
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    //productモデルとのリレーション
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}