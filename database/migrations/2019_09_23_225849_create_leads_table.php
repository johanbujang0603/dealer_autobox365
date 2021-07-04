<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateLeadsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('leads', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->string('gender', 191)->nullable();
			$table->string('first_name', 191)->nullable();
			$table->string('last_name', 191)->nullable();
			$table->string('email', 191)->nullable();
			$table->string('phone_number', 191)->nullable();
			$table->string('country_base_phone', 191)->nullable();
			$table->string('country_base_residence', 191)->nullable();
			$table->string('messaging_apps', 191)->nullable();
			$table->string('photo', 191)->nullable();
			$table->string('tags', 191)->nullable();
			$table->date('converted_at')->nullable();
			$table->integer('user_id')->nullable();
			$table->timestamps();
			$table->string('profile_image')->nullable();
			$table->string('middle_name')->nullable();
			$table->string('civility')->nullable();
			$table->integer('is_converted')->default(0);
			$table->string('address', 191)->nullable();
			$table->string('city', 191)->nullable();
			$table->string('postal_code', 191)->nullable();
			$table->string('facebook_url', 191)->nullable();
			$table->integer('status')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('leads');
	}

}
