<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateLocationSocialsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('location_socials', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->integer('location_id');
			$table->string('social_medial', 191);
			$table->timestamps();
			$table->string('social_url', 191)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('location_socials');
	}

}
