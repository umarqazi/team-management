<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

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
        ]);

        DB::table('roles')->insert([
            'name' => 'pm',
        ]);

        DB::table('roles')->insert([
            'name' => 'teamlead',
        ]);

        DB::table('roles')->insert([
            'name' => 'developer',
        ]);

        DB::table('roles')->insert([
            'name' => 'engineer',
        ]);

        DB::table('roles')->insert([
            'name' => 'sales',
        ]);
    }
}
