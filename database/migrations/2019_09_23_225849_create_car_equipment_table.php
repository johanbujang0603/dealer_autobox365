<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCarEquipmentTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('car_equipment', function(Blueprint $table)
		{
			$table->integer('id_car_equipment', true)->comment('id');
			$table->integer('id_car_trim')->index('fk_car_equipment_car_modification')->comment('Trim');
			$table->string('name');
			$table->integer('year')->nullable();
			$table->integer('date_create')->unsigned();
			$table->integer('date_update')->unsigned();
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
		Schema::drop('car_equipment');
	}

}
