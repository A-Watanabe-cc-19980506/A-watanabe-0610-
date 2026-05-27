<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id')->notNull();
            $table->string('last_name_kana')->notNull();
            $table->string('first_name_kana')->notNull();
            $table->string('last_name_kanji')->notNull();
            $table->string('first_name_kanji')->notNull();
            $table->string('email')->notNull();
            $table->string('password')->notNull();
            $table->boolean('e-mail_subscription')->default(false)->nullable();
            $table->integer('role')->notNull()->comment('0:一般ユーザー,1:管理者');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}