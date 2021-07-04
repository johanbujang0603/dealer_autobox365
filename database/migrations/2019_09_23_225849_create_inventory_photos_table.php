<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateInventoryPhotosTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('inventory_photos', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->integer('inventory_id')->nullable();
			$table->string('file_name')->nullable();
			$table->string('upload_path', 191);
			$table->integer('order_no')->default(0);
			$table->integer('user_id')->default(0);
			$table->timestamps();
			$table->float('file_size', 10, 0)->nullable();
			$table->string('source', 191)->default('local');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('inventory_photos');
	}

}
