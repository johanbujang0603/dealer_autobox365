<?php

use Illuminate\Database\Seeder;

class PermissionAppsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('permission_apps')->insert([
            'app_name' => 'Inventory',
            'app_name_slug' => 'inventory',
        ]);
        DB::table('permission_apps')->insert([
            'app_name' => 'Lead',
            'app_name_slug' => 'lead',
        ]);
        DB::table('permission_apps')->insert([
            'app_name' => 'Customer',
            'app_name_slug' => 'customer',
        ]);
        DB::table('permission_apps')->insert([
            'app_name' => 'User',
            'app_name_slug' => 'user',
        ]);
        DB::table('permission_apps')->insert([
            'app_name' => 'Location',
            'app_name_slug' => 'location',
        ]);
        DB::table('permission_apps')->insert([
            'app_name' => 'Document',
            'app_name_slug' => 'document',
        ]);
        DB::table('permission_apps')->insert([
            'app_name' => 'Report',
            'app_name_slug' => 'report',
        ]);
        DB::table('permission_apps')->insert([
            'app_name' => 'Transaction',
            'app_name_slug' => 'transaction',
        ]);
    }
}
