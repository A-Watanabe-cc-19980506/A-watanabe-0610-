<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductVariation;
use App\Models\Color;
use App\Models\Size;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // 1. カテゴリーデータの登録（すでに存在する場合はスキップ）
        if (\Schema::hasTable('categories')) {
            DB::table('categories')->insertOrIgnore([
                ['id' => 1, 'name' => 'トップス', 'created_at' => now(), 'updated_at' => now()],
                ['id' => 2, 'name' => 'パンツ', 'created_at' => now(), 'updated_at' => now()],
            ]);
        }

        // 2. カラーデータの登録（codeなしの安全版）
        $colors = [
            ['name' => 'レッド'],
            ['name' => 'ブルー'],
            ['name' => 'ブラック'],
            ['name' => 'ホワイト'],
        ];
        $colorModels = [];
        foreach ($colors as $color) {
            $colorModels[] = Color::create($color);
        }

        // 3. サイズデータの登録
        $sizes = ['S', 'M', 'L', 'XL'];
        $sizeModels = [];
        foreach ($sizes as $size) {
            $sizeModels[] = Size::create(['name' => $size]);
        }

        // 4. 商品データの登録（img_path と category_id を完全網羅）
        $products = [
            [
                'name' => 'ベーシック Tシャツ',
                'description' => '着心地抜群の定番Tシャツです。',
                'price' => 2000,
                'img_path' => 'products/dummy_tshirt.jpg',
                'category_id' => 1,
            ],
            [
                'name' => 'スタイリッシュ パーカー',
                'description' => 'シンプルなデザインで着回しやすいパーカー。',
                'price' => 5500,
                'img_path' => 'products/dummy_hoodie.jpg',
                'category_id' => 1,
            ],
            [
                'name' => 'ストレッチ チノパンツ',
                'description' => '動きやすい伸縮性のあるチノパンツです。',
                'price' => 4200,
                'img_path' => 'products/dummy_pants.jpg',
                'category_id' => 2,
            ],
        ];

        foreach ($products as $productData) {
            $product = Product::create($productData);

            // 各商品にカラー×サイズの全バリエーションを紐付け
            foreach ($colorModels as $color) {
                foreach ($sizeModels as $size) {
                    ProductVariation::create([
                        'product_id' => $product->id,
                        'color_id'   => $color->id,
                        'size_id'    => $size->id,
                        'stock'      => rand(1, 50), // 在庫を1〜50でランダム生成
                    ]);
                }
            }
        }
    }
}
