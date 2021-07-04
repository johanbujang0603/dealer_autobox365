<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCarModelTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('car_model', function(Blueprint $table)
		{
			$table->integer('id_car_model', true)->comment('ID');
			$table->integer('id_car_make')->index('fk_car_model_car_make')->comment('Make');
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
		Schema::drop('car_model');
	}

}
