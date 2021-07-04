<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateLocationPhotosTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('location_photos', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->integer('location_id');
			$table->string('upload_path', 191);
			$table->timestamps();
			$table->string('file_name', 191);
			$table->string('file_size', 191);
			$table->string('user_id', 191);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('location_photos');
	}

}
