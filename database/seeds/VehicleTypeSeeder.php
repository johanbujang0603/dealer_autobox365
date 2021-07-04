<?php

use Illuminate\Database\Seeder;

class VehicleTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('inventory_types')->insert([
            'type_name' => 'Car',
            'slug' => 'car',
        ]);
        DB::table('inventory_types')->insert([
            'type_name' => 'Trucks',
            'slug' => 'trucks',
        ]);
        DB::table('inventory_types')->insert([
            'type_name' => 'Motorbikes',
            'slug' => 'motorbikes',
        ]);
    }
}
