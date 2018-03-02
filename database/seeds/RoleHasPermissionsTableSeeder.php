<?php

use Illuminate\Database\Seeder;

class RoleHasPermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /*ADMIN PERMISSIONS*/
        /*Users Permissions Assigned To Admin*/
        DB::table('role_has_permissions')->insert([
            'permission_id' => '1',
            'role_id'       => '1',
        ]);
        DB::table('role_has_permissions')->insert([
            'permission_id' => '2',
            'role_id'       => '1',
        ]);
        DB::table('role_has_permissions')->insert([
            'permission_id' => '3',
            'role_id'       => '1',
        ]);

        /*Project Permissions Assigned To Admin*/
        DB::table('role_has_permissions')->insert([
            'permission_id' => '4',
            'role_id'       => '1',
        ]);
        DB::table('role_has_permissions')->insert([
            'permission_id' => '5',
            'role_id'       => '1',
        ]);
        DB::table('role_has_permissions')->insert([
            'permission_id' => '6',
            'role_id'       => '1',
        ]);

        /*Task Permissions Assigned To Admin*/
        DB::table('role_has_permissions')->insert([
            'permission_id' => '7',
            'role_id'       => '1',
        ]);
        DB::table('role_has_permissions')->insert([
            'permission_id' => '8',
            'role_id'       => '1',
        ]);
        DB::table('role_has_permissions')->insert([
            'permission_id' => '9',
            'role_id'       => '1',
        ]);

        /*TEAM LEAD PERMISSIONS*/
        /*Project Permissions Assigned To Team lead*/
        DB::table('role_has_permissions')->insert([
            'permission_id' => '4',
            'role_id'       => '2',
        ]);
        DB::table('role_has_permissions')->insert([
            'permission_id' => '5',
            'role_id'       => '2',
        ]);
        DB::table('role_has_permissions')->insert([
            'permission_id' => '6',
            'role_id'       => '2',
        ]);

        /*Task Permissions Assigned To Team lead*/
        DB::table('role_has_permissions')->insert([
            'permission_id' => '7',
            'role_id'       => '2',
        ]);
        DB::table('role_has_permissions')->insert([
            'permission_id' => '8',
            'role_id'       => '2',
        ]);
        DB::table('role_has_permissions')->insert([
            'permission_id' => '9',
            'role_id'       => '2',
        ]);
    }
}
