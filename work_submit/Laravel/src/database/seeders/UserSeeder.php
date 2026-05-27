<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    DB::table('users')->insert([
      'last_name_kana' => 'カンリシャ',
      'first_name_kana' => 'タロウ',
      'last_name_kanji' => '管理者',
      'first_name_kanji' => '太郎',
      'email' => 'admin000@sample.com',
      'password' => Hash::make('admin000'),
      'role' => 1,
    ]);
  }
}
