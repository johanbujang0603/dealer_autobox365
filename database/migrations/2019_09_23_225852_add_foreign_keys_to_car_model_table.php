<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToCarModelTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('car_model', function(Blueprint $table)
		{
			$table->foreign('id_car_make', 'fk_car_model_car_make')->references('id_car_make')->on('car_make')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('id_car_type', 'fk_car_model_car_type')->references('id_car_type')->on('car_type')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('car_model', function(Blueprint $table)
		{
			$table->dropForeign('fk_car_model_car_make');
			$table->dropForeign('fk_car_model_car_type');
		});
	}

}
