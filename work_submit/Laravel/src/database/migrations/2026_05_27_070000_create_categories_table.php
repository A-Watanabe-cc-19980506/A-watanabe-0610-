<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('カテゴリ名: メンズ、レディース、キッズ');
            $table->foreignId('parent_id')->nullable()->constrained('categories')->onDelete('cascade');
            $table->tinyInteger('level')->default(1)->comment('カテゴリの階層');
            $table->integer('sort')->default(0)->comment('並び順用の数値');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};