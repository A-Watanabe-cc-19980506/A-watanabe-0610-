<?php
// 商品一覧（第1階層）
Breadcrumbs::for('products.index', function ($trail) {
    $trail->push('商品一覧', route('products.index'));
});

// カテゴリ別ページ（第2階層）
Breadcrumbs::for('products.category', function ($trail, $category) {
    $trail->parent('products.index');
    $trail->push($category->name, route('products.category', $category->id));
});