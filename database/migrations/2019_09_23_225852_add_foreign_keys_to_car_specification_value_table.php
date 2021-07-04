<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToCarSpecificationValueTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('car_specification_value', function(Blueprint $table)
		{
			$table->foreign('id_car_specification', 'fk_car_specification_value_car_specification')->references('id_car_specification')->on('car_specification')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('id_car_trim', 'fk_car_specification_value_car_trim')->references('id_car_trim')->on('car_trim')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('id_car_type', 'fk_car_specification_value_car_type')->references('id_car_type')->on('car_type')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('car_specification_value', function(Blueprint $table)
		{
			$table->dropForeign('fk_car_specification_value_car_specification');
			$table->dropForeign('fk_car_specification_value_car_trim');
			$table->dropForeign('fk_car_specification_value_car_type');
		});
	}

}
