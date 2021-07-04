<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUserPhonesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('user_phones', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->timestamps();
			$table->integer('user_id')->nullable();
			$table->boolean('valid')->nullable();
			$table->string('number')->nullable();
			$table->string('mobile_no')->nullable();
			$table->string('local_format')->nullable();
			$table->string('international_format')->nullable();
			$table->float('country_prefix', 10, 0)->nullable();
			$table->string('country_code')->nullable();
			$table->string('country_name')->nullable();
			$table->string('location')->nullable();
			$table->string('carrier')->nullable();
			$table->string('line_type')->nullable();
			$table->string('messaging_apps')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('user_phones');
	}

}
