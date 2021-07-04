<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToCarOptionTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('car_option', function(Blueprint $table)
		{
			$table->foreign('id_parent', 'fk_car_option_car_option')->references('id_car_option')->on('car_option')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('id_car_type', 'fk_car_option_car_type')->references('id_car_type')->on('car_type')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('car_option', function(Blueprint $table)
		{
			$table->dropForeign('fk_car_option_car_option');
			$table->dropForeign('fk_car_option_car_type');
		});
	}

}
