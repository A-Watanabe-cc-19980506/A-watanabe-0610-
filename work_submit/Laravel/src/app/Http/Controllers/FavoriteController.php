<?php

namespace App\Http\Controllers;
use App\Models\Favorite;
use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    //新規お気に入り追加
    public function store(Request $request, Product $product)
    {
        Favorite::create([
            'user_id' => Auth::id(),
            'product_id' => $product->id,
        ]);

        return response()->json(['status' => 'added']);
    }

    public function destroy(Product $product)
    {
        Favorite::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->delete();

        return response()->json(['status' => 'removed']);
    }

}