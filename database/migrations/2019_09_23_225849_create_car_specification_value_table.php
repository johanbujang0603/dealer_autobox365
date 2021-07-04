<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCarSpecificationValueTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('car_specification_value', function(Blueprint $table)
		{
			$table->integer('id_car_specification_value', true)->comment('id');
			$table->integer('id_car_trim')->index('fk_car_characteristic_value_car_modification')->comment('Trim');
			$table->integer('id_car_specification')->index('fk_car_specification_value_car_specification')->comment('Specification');
			$table->string('value', 500)->nullable()->index('value_2');
			$table->string('unit')->nullable()->comment('Unit');
			$table->integer('date_create')->unsigned()->nullable();
			$table->integer('date_update')->unsigned()->nullable();
			$table->integer('id_car_type')->nullable()->index('id_car_type');
			$table->index(['value','id_car_type'], 'value');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('car_specification_value');
	}

}
