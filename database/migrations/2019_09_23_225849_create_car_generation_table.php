<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCarGenerationTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('car_generation', function(Blueprint $table)
		{
			$table->integer('id_car_generation', true);
			$table->integer('id_car_model')->nullable()->index('fk_car_generation_car_model')->comment('ID');
			$table->string('name');
			$table->string('year_begin')->nullable();
			$table->string('year_end')->nullable();
			$table->integer('date_create')->unsigned();
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
		Schema::drop('car_generation');
	}

}
