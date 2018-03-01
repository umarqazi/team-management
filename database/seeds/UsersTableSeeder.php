<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'admin',
            'email' => 'shaban@gems.techverx.com',
            'password' => bcrypt('techverx321'),
            'created_at' =>Carbon::now(),
            'updated_at' =>Carbon::now(),
        ]);
    }
}
