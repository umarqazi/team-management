<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;

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
            'created_at' =>Carbon::now(),
            'updated_at' =>Carbon::now(),
        ]);

        DB::table('permissions')->insert([
            'name' => 'edit user',
            'created_at' =>Carbon::now(),
            'updated_at' =>Carbon::now(),
        ]);

        DB::table('permissions')->insert([
            'name' => 'delete user',
            'created_at' =>Carbon::now(),
            'updated_at' =>Carbon::now(),
        ]);

        /* team-management Projects Permissions */
        DB::table('permissions')->insert([
            'name' => 'create project',
            'created_at' =>Carbon::now(),
            'updated_at' =>Carbon::now(),
        ]);

        DB::table('permissions')->insert([
            'name' => 'edit project',
            'created_at' =>Carbon::now(),
            'updated_at' =>Carbon::now(),
        ]);

        DB::table('permissions')->insert([
            'name' => 'delete project',
            'created_at' =>Carbon::now(),
            'updated_at' =>Carbon::now(),
        ]);

        /* team-management Tasks Permissions */
        DB::table('permissions')->insert([
            'name' => 'create task',
            'created_at' =>Carbon::now(),
            'updated_at' =>Carbon::now(),
        ]);

        DB::table('permissions')->insert([
            'name' => 'edit task',
            'created_at' =>Carbon::now(),
            'updated_at' =>Carbon::now(),
        ]);

        DB::table('permissions')->insert([
            'name' => 'delete task',
            'created_at' =>Carbon::now(),
            'updated_at' =>Carbon::now(),
        ]);
    }
}
