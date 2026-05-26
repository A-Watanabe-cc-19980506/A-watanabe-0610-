<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class NewDate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->timestamp('rest_password_expire_data')->nullable()->comment('パスワード再設定キーの有効期限');
        $table->string('rest_password_access_key', 64)->nullable()->unique()->comment('パスワード再設定キー');
            });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['rest_password_expire_data', 'rest_password_access_key']);
        });
    }
}
