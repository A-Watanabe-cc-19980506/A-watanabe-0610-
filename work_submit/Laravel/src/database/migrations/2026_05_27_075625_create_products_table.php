<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('商品名');
            $table->integer('price')->unsigned()->comment('金額');
            $table->string('img_path')->notNull()->comment('商品画像の保存先パス');
            $table->text('description')->nullable()->comment('商品説明');
            $table->foreignId('category_id')->constrained()->cascadeOnDelete()->comment('カテゴリID');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};