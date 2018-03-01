<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /* team-management Roles */
        DB::table('roles')->insert([
            'name' => 'admin',
            'created_at' =>Carbon::now(),
            'updated_at' =>Carbon::now(),
        ]);
        DB::table('roles')->insert([
            'name' => 'teamlead',
            'created_at' =>Carbon::now(),
            'updated_at' =>Carbon::now(),
        ]);
        DB::table('roles')->insert([
            'name' => 'developer',
            'created_at' =>Carbon::now(),
            'updated_at' =>Carbon::now(),
        ]);
        DB::table('roles')->insert([
            'name' => 'engineer',
            'created_at' =>Carbon::now(),
            'updated_at' =>Carbon::now(),
        ]);
        DB::table('roles')->insert([
            'name' => 'sales',
            'created_at' =>Carbon::now(),
            'updated_at' =>Carbon::now(),
        ]);
    }
}
