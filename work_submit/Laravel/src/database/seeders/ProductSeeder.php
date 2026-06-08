<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // 1. カラーマスタの登録
        $colorWhiteId = DB::table('colors')->insertGetId([
            'name' => 'ホワイト', 'color_code' => '#FFFFFF'
        ]);
        $colorBlackId = DB::table('colors')->insertGetId([
            'name' => 'ブラック', 'color_code' => '#000000'
        ]);
        $colorNavyId = DB::table('colors')->insertGetId([
            'name' => 'ネイビー', 'color_code' => '#000080'
        ]);

        // 2. サイズマスタの登録
        $sizeSId = DB::table('sizes')->insertGetId(['name' => 'S']);
        $sizeMId = DB::table('sizes')->insertGetId(['name' => 'M']);
        $sizeLId = DB::table('sizes')->insertGetId(['name' => 'L']);

        // 3. カテゴリの登録（1階層フラット）
        $catMensId = DB::table('categories')->insertGetId([
            'name' => 'メンズ', 'parent_id' => null, 'level' => 1, 'sort' => 1, 'created_at' => now(), 'updated_at' => now()
        ]);
        $catWomensId = DB::table('categories')->insertGetId([
            'name' => 'レディース', 'parent_id' => null, 'level' => 1, 'sort' => 2, 'created_at' => now(), 'updated_at' => now()
        ]);
        $catKidsId = DB::table('categories')->insertGetId([
            'name' => 'キッズ', 'parent_id' => null, 'level' => 1, 'sort' => 3, 'created_at' => now(), 'updated_at' => now()
        ]);

        // 4. 親商品データ（6つ分）の定義（public内のパスを指定）
        $productsData = [
            // ■ メンズ (2つの商品)
            [
                'cat_id' => $catMensId, 'name' => 'プレミアム オックスフォードシャツ', 'price' => 4980,
                'desc' => '着心地抜群のオールシーズン使える定番オックスフォードシャツです。',
                'imgs' => [
                    '/img/products/m_shirt_main.jpg',
                    '/img/products/m_shirt_sub.jpg',
                    '/img/products/m_shirt_sub2.jpg',
                    '/img/products/m_shirt_sub3.jpg'
                ], // 💡 public/images/m_shirt_main.jpg を参照
                'colors' => [$colorWhiteId, $colorBlackId]
            ],
            [
                'cat_id' => $catMensId, 'name' => 'ストレッチ テーパードチノパンツ', 'price' => 5980,
                'desc' => '細身のシルエットながら、抜群 of ストレッチ性で動きやすいチノパンツ。',
                'imgs' => ['/img/products/m_pants_main.jpg'],
                'colors' => [$colorBlackId, $colorNavyId]
            ],
            // ■ レディース (2つの商品)
            [
                'cat_id' => $catWomensId, 'name' => 'シルキータッチ Vネックブラウス', 'price' => 3980,
                'desc' => '上品な光沢感と滑らかな肌触りが特徴のオフィスワークにも最適なブラウス。',
                'imgs' => ['/img/products/w_blouse_main.jpg'],
                'colors' => [$colorWhiteId, $colorNavyId]
            ],
            [
                'cat_id' => $catWomensId, 'name' => 'フレア マキシスカート', 'price' => 4500,
                'desc' => '歩くたびに美しく揺れるボリューム感が魅力のフェミニンなフレアスカート。',
                'imgs' => ['/img/products/w_skirt_main.jpg'],
                'colors' => [$colorBlackId, $colorNavyId]
            ],
            // ■ キッズ (2つの商品)
            [
                'cat_id' => $catKidsId, 'name' => '丸洗い対応 オーガニックコットンTシャツ', 'price' => 1980,
                'desc' => 'お肌に優しいオーガニックコットン100%。何度洗濯してもヘタレにくい頑丈仕様。',
                'imgs' => ['/img/products/k_tshirt_main.jpg'],
                'colors' => [$colorWhiteId, $colorNavyId]
            ],
            [
                'cat_id' => $catKidsId, 'name' => 'らくらくストレッチ デニムパンツ', 'price' => 2980,
                'desc' => '元気に動き回るお子様にぴったりな、ウエストゴム仕様の超ストレッチデニム。',
                'imgs' => ['/img/products/k_denim_main.jpg'],
                'colors' => [$colorNavyId]
            ],
        ];

        // 5. ループ処理で親・画像・バリエーションを連動して一気に登録
        foreach ($productsData as $p) {
            // 親商品の保存
            $pId = DB::table('products')->insertGetId([
                'name' => $p['name'],
                'price' => $p['price'],
                'description' => $p['desc'],
                'category_id' => $p['cat_id'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // 画像の保存（複数対応）
            foreach ($p['imgs'] as $index => $url) {
                DB::table('product_imgs')->insert([
                    'product_id' => $pId,
                    'img_url' => $url,
                    'sort' => $index,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // バリエーション（SKU）の保存（カラー × サイズ M/L の組み合わせを自動生成）
            foreach ($p['colors'] as $cId) {
                foreach ([$sizeSId,$sizeMId, $sizeLId] as $sId) {
                    DB::table('product_variations')->insert([
                        'product_id' => $pId,
                        'color_id' => $cId,
                        'size_id' => $sId,
                        'stock' => rand(0, 15), // 💡 10〜50個の在庫をランダムで自動割り当て
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }
}