<?php

use Illuminate\Database\Seeder;

class AdminTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('admins')->insert([
            'name' => 'Administrator',
            'email' => 'admin@autobox365.com',
            'password' => bcrypt('admin@1234'),
        ]);
    }
}
