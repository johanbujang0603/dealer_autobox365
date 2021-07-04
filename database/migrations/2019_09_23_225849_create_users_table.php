<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->string('name', 191);
			$table->string('email', 191)->unique();
			$table->dateTime('email_verified_at')->nullable();
			$table->string('password', 191);
			$table->string('remember_token', 100)->nullable();
			$table->timestamps();
			$table->string('first_name', 191);
			$table->string('last_name', 191);
			$table->string('gender', 191);
			$table->string('photo_src', 191)->nullable();
			$table->string('role', 191)->nullable();
			$table->string('location', 191)->nullable();
			$table->string('authorisations', 191)->nullable();
			$table->enum('type', array('Dealer','User'))->nullable();
			$table->integer('dealer_id')->default(0);
			$table->string('profile_image', 191)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('users');
	}

}
