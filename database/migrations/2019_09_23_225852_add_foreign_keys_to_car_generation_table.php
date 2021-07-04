<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToCarGenerationTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('car_generation', function(Blueprint $table)
		{
			$table->foreign('id_car_model', 'fk_car_generation_car_model')->references('id_car_model')->on('car_model')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('id_car_type', 'fk_car_generation_car_type')->references('id_car_type')->on('car_type')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('car_generation', function(Blueprint $table)
		{
			$table->dropForeign('fk_car_generation_car_model');
			$table->dropForeign('fk_car_generation_car_type');
		});
	}

}
