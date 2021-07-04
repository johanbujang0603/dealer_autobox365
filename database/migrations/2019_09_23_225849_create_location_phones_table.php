<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateLocationPhonesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('location_phones', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->integer('location_id');
			$table->string('country_code', 191)->nullable();
			$table->string('mobile_no', 191);
			$table->timestamps();
			$table->enum('valid', array('true','false'))->nullable()->default('false');
			$table->string('number', 191)->nullable();
			$table->string('local_format', 191)->nullable();
			$table->string('international_format', 191)->nullable();
			$table->string('country_prefix', 191)->nullable();
			$table->string('country_name', 191)->nullable();
			$table->string('location', 191)->nullable();
			$table->string('carrier', 191)->nullable();
			$table->string('line_type', 191)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('location_phones');
	}

}
