<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Testing\Fluent\Concerns\Has;

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
            [
                'name' => 'webapp_admin',
                'email' => 'petreniuk.ua@gmail.com',
                'password' => Hash::make('lutsk123ns')
            ],
            [
                'name' => 'webapp_owner',
                'email' => 'webapp_owner@gmail.com',
                'password' => Hash::make('123578daqkd')
            ]
        ]);
    }
}
