<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToCarOptionValueTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('car_option_value', function(Blueprint $table)
		{
			$table->foreign('id_car_equipment', 'fk_car_option_value_car_equipment')->references('id_car_equipment')->on('car_equipment')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('id_car_option', 'fk_car_option_value_car_option')->references('id_car_option')->on('car_option')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('id_car_type', 'fk_car_option_value_car_type')->references('id_car_type')->on('car_type')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('car_option_value', function(Blueprint $table)
		{
			$table->dropForeign('fk_car_option_value_car_equipment');
			$table->dropForeign('fk_car_option_value_car_option');
			$table->dropForeign('fk_car_option_value_car_type');
		});
	}

}
