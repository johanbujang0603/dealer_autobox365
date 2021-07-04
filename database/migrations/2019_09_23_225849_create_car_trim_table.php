<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCarTrimTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('car_trim', function(Blueprint $table)
		{
			$table->integer('id_car_trim', true)->comment('id');
			$table->integer('id_car_serie')->nullable()->index('fk_car_trim_car_serie')->comment('ID');
			$table->integer('id_car_model')->nullable()->index('fk_car_trim_car_model')->comment('ID');
			$table->string('name');
			$table->integer('start_production_year')->nullable();
			$table->integer('end_production_year')->nullable();
			$table->integer('date_create')->unsigned()->nullable();
			$table->integer('date_update')->unsigned()->nullable();
			$table->integer('id_car_type')->nullable()->index('id_car_type');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('car_trim');
	}

}
