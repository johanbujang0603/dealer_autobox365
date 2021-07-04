<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateInventoryTagsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('inventory_tags', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->string('tag_name', 191);
			$table->integer('user_id')->default(0);
			$table->timestamps();
			$table->string('color')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('inventory_tags');
	}

}
