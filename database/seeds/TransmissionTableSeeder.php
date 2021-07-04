<?php

use Illuminate\Database\Seeder;

class TransmissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //transmissions
        DB::table('transmissions')->insert([
            'transmission' => 'Automatic'
        ]);
        DB::table('transmissions')->insert([
            'transmission' => 'Manual'
        ]);
    }
}
