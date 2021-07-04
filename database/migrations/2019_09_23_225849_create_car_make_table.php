<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCarMakeTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('car_make', function(Blueprint $table)
		{
			$table->integer('id_car_make', true)->comment('id');
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
		Schema::drop('car_make');
	}

}
