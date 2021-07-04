<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCarOptionTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('car_option', function(Blueprint $table)
		{
			$table->integer('id_car_option', true);
			$table->string('name')->nullable()->index('name')->comment('Name');
			$table->integer('id_parent')->nullable()->index('fk_car_option_car_option');
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
		Schema::drop('car_option');
	}

}
