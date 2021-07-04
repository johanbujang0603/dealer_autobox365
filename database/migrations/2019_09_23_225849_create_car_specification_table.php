<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCarSpecificationTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('car_specification', function(Blueprint $table)
		{
			$table->integer('id_car_specification', true)->comment('id');
			$table->string('name')->comment('Название');
			$table->integer('id_parent')->nullable()->index('fk_car_characteristic_car_characteristic')->comment('id');
			$table->integer('date_create')->unsigned()->nullable();
			$table->integer('date_update')->unsigned()->nullable();
			$table->integer('id_car_type')->nullable()->index('id_car_type');
			$table->unique(['name','id_parent','id_car_type'], 'name_2');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('car_specification');
	}

}
