<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCustomersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('customers', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->string('gender', 191);
			$table->string('first_name', 191);
			$table->string('last_name', 191);
			$table->string('email', 191);
			$table->string('phone_number', 191);
			$table->string('country_base_phone', 191);
			$table->string('country_base_residence', 191);
			$table->string('messaging_apps', 191);
			$table->string('photo', 191);
			$table->string('tags', 191);
			$table->integer('user_id');
			$table->timestamps();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('customers');
	}

}
