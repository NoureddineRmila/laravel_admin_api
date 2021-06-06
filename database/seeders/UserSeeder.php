<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('users')->insert([
            'name' => 'GMC',
            'email' => 'admin@email.com',
            'role' => 'admin',
            'password' => bcrypt('password')
        ]);
    }
}
