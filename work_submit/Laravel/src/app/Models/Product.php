<?php

namespace App\Models;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'price',
        'image_path',
        'category_id',
        'description',
    ];

    protected $appends = [
        'category',
        'taxed_price'
    ];

    public function category(){
        return $this->belongsTo('App\Models\Category');
    }

    public function getCategoryAttribute(){
        return Category::find($this->category_id)->category;
    }

    public function getTaxedPriceAttribute(){
        return $this->price * 1.10;
    }
    
}
