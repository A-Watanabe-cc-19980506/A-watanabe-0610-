<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id(); // bigint unsigned / 主キー
            
            // carts.idと紐付け
            $table->foreignId('cart_id')->constrained('carts')->onDelete('cascade')->comment('カートID');
            
            // 商品バリidと紐付け（※一般的なキャメル/スネークケースに合わせて product_variation_id としています）
            $table->foreignId('product_variation_id')->constrained('product_variations')->onDelete('cascade')->comment('商品バリ_ID');
            
            $table->integer('quantity')->default(1)->comment('買い物かごに入れた個数');
            $table->tinyInteger('is_checked')->default(1)->comment('選択フラグ（1:選択、0:未選択）');
            $table->timestamps(); // created_at, updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_items');
    }
};