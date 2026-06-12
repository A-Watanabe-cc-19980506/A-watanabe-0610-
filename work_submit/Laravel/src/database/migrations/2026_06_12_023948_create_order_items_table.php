<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            // どの注文に紐づくか（上のordersテーブルのidとガッチャンコします）
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            // どの商品か
            $table->foreignId('product_variation_id')->constrained()->onDelete('cascade');
            $table->integer('quantity'); // 数量
            $table->integer('price');    // 購入時の単価
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_items');
    }
}
