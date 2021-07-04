<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(VehicleTypeSeeder::class);
        $this->call(CurrenciesTableSeeder::class);
        $this->call(TransmissionTableSeeder::class);
        $this->call(PermissionAppsSeeder::class);
    }
}
