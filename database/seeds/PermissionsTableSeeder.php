<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /* team-management Users Permissions */
        DB::table('permissions')->insert([
            'name' => 'create user',
        ]);

        DB::table('permissions')->insert([
            'name' => 'edit user',
        ]);

        DB::table('permissions')->insert([
            'name' => 'delete user',
        ]);

        /* team-management Projects Permissions */
        DB::table('permissions')->insert([
            'name' => 'create project',
        ]);

        DB::table('permissions')->insert([
            'name' => 'edit project',
        ]);

        DB::table('permissions')->insert([
            'name' => 'delete project',
        ]);

        /* team-management Tasks Permissions */
        DB::table('permissions')->insert([
            'name' => 'create task',
        ]);

        DB::table('permissions')->insert([
            'name' => 'edit task',
        ]);

        DB::table('permissions')->insert([
            'name' => 'delete task',
        ]);
    }
}
