<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();

            // 外部キー制約（親データが消えたら連動して消えるよう cascadeOnDelete を設定）
            $table->foreignId('product_id')->constrained()->cascadeOnDelete()->comment('商品ID');
            $table->foreignId('color_id')->constrained()->cascadeOnDelete()->comment('カラーID');
            $table->foreignId('size_id')->constrained()->cascadeOnDelete()->comment('サイズID');

            $table->integer('price')->unsigned()->comment('価格（サイズや色で変動可能）');
            $table->integer('stock')->unsigned()->default(0)->comment('在庫数');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();

            // 実務の要：商品ID × カラーID × サイズID の組み合わせが重複しないようにユニーク制約をかける
            $table->unique(['product_id', 'color_id', 'size_id'], 'product_color_size_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};