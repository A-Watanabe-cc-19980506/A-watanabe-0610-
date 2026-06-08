<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Color;
use App\Models\Size;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * 商品一覧表示（検索・絞り込み付き）
     */

    public function index(Request $request)
    {
        // 1. 画面のセレクトボックス表示に必要なデータをマスターから取得（常に必要）
        $categories = Category::pluck('name', 'id');
        $colors = Color::pluck('name', 'id');
        $sizes = Size::pluck('name', 'id');

        // 2. クエリの準備（Productを主役に統一！）
        $query = Product::with('variants', 'imgs');

        // 3. カテゴリ絞り込み（値があるときだけ実行される）
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // 4. カラー絞り込み
        if ($request->filled('color')) {
            $query->whereHas('variants', function ($q) use ($request) {
                $q->where('color_id', $request->color);
            });
        }

        // 5. サイズ絞り込み
        if ($request->filled('size')) {
            $query->whereHas('variants', function ($q) use ($request) {
                $q->where('size_id', $request->size);
            });
        }

        // 6. 在庫ありのみ絞り込み
        if ($request->boolean('in_stock')) {
            $query->whereHas('variants', function ($q) {
                $q->where('stock', '>', 0);
            });
        }

        // 7. キーワード検索
        if ($request->filled('keyword')) {
            $keyword = $request->input('keyword');
            $query->where('name', 'LIKE', "%{$keyword}%");
        }

        // 8. 価格帯での絞り込み
        if ($request->filled('price_range')) {
            switch ($request->price_range) {
                case 'under_1000':
                    $query->where('price', '<=', 1000);
                    break;
                case '1000_to_3000':
                    $query->whereBetween('price', [1001, 3000]);
                    break;
                case 'over_3000':
                    $query->where('price', '>=', 3001);
                    break;
            }
        }

        // 9. 並び替え（ソート）機能
        if ($request->filled('sort')) {
            switch ($request->sort) {
                case 'price_asc': // 価格の安い順
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_desc': // 価格の高い順
                    $query->orderBy('price', 'desc');
                    break;
                case 'latest': // 新着順
                default:
                    $query->orderBy('created_at', 'desc');
                    break;
            }
        } else {
            // 初期表示など、指定がないときのデフォルトは新着順
            $query->orderBy('created_at', 'desc');
        }

        // 10. 最後にデータを取得（一括してページネーション）
        $products = $query->paginate(6);

        // 共通のビューにすべての変数を渡して表示
        return view("products.index", compact('products', 'categories', 'colors', 'sizes'));
    }
    // /* 商品詳細表示 */
    public function show($id)
    {
        // variants の color, size を eager load して JSON シリアライズ時に名前情報が含まれるようにする
        $product = Product::with(['variants.color', 'variants.size', 'imgs','category','productFavorites'])->findOrFail($id);
        $allColors = Color::all();
        $allSizes = Size::all();
        $category = $product->category;
        // views/products/show.blade.php を返す
        return view('products.show', compact('product','allColors','allSizes','category'));
    }
}