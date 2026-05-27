<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SearchController extends Controller
{
    //public function search(Request $request)
{
    // 商品（Product）のクエリを初期化
    $query = Product::query();

    // 1. キーワード検索
    $query->when($request->filled('keyword'), function ($q) use ($request) {
        $q->where('name', 'like', '%' . $request->keyword . '%');
    });

    // 2. カテゴリ絞り込み（配列で届くので whereIn を使用）
    $query->when($request->has('categories'), function ($q) use ($request) {
        $q->whereIn('category_name', $request->categories);
    });

    // 3. サイズ絞り込み
    $query->when($request->has('sizes'), function ($q) use ($request) {
        $q->whereIn('size', $request->sizes);
    });

    // 4. カラー絞り込み
    $query->when($request->has('colors'), function ($q) use ($request) {
        $q->whereIn('color', $request->colors);
    });

    // 5. 価格帯絞り込み（~円以下）
    $query->when($request->filled('price_max'), function ($q) use ($request) {
        $q->where('price', '<=', $request->price_max);
    });

    // 結果を取得
    $products = $query->latest()->paginate(20);

    return view('home', compact('products'));
}
}
