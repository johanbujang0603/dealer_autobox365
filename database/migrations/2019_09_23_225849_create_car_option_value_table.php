<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCarOptionValueTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('car_option_value', function(Blueprint $table)
		{
			$table->integer('id_car_option_value', true);
			$table->integer('id_car_option')->nullable();
			$table->integer('id_car_equipment')->nullable()->index('fk_car_option_value_car_equipment')->comment('id');
			$table->boolean('is_base')->default(1);
			$table->integer('date_create')->unsigned();
			$table->integer('date_update')->unsigned()->nullable();
			$table->integer('id_car_type')->nullable()->index('date_delete');
			$table->unique(['id_car_option','id_car_equipment','id_car_type'], 'id_car_option');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('car_option_value');
	}

}
