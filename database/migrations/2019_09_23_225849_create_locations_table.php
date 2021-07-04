<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateLocationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('locations', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->integer('user_id')->nullable()->default(0);
			$table->string('name', 191)->nullable();
			$table->string('address', 191)->nullable();
			$table->string('country', 191)->nullable();
			$table->string('state', 191)->nullable();
			$table->string('city', 191)->nullable();
			$table->string('full_address', 191)->nullable();
			$table->string('email', 191)->nullable();
			$table->string('website', 191)->nullable();
			$table->string('type', 191)->nullable();
			$table->text('description', 65535)->nullable();
			$table->string('logo', 191)->nullable();
			$table->timestamps();
			$table->integer('is_draft')->default(0);
			$table->integer('is_deleted')->default(0);
			$table->float('latitude', 10, 0)->default(0);
			$table->float('longitude', 10, 0)->default(0);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('locations');
	}

}
