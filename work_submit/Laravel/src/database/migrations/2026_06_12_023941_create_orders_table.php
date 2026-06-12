<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // 誰が買ったか
            $table->integer('total_price'); // 合計金額
            $table->string('stripe_session_id')->unique(); // StripeセッションID
            $table->string('status')->default('paid'); // ステータス

            // 配送先情報（選択肢1の仕様）
            $table->string('name');         // お名前
            $table->string('zipcode');      // 郵便番号
            $table->string('address');      // ご住所
            $table->string('building')->nullable(); // 建物名（空欄OK）

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
        Schema::dropIfExists('orders');
    }
}
