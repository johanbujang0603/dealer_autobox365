<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCarSerieTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('car_serie', function(Blueprint $table)
		{
			$table->integer('id_car_serie', true)->comment('ID');
			$table->integer('id_car_model')->nullable()->index('fk_car_serie_car_model')->comment('ID');
			$table->integer('id_car_generation')->nullable()->index('fk_car_serie_car_generation');
			$table->string('name');
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
		Schema::drop('car_serie');
	}

}
