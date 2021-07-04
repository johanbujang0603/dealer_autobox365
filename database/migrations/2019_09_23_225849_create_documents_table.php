<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDocumentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('documents', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->string('original_name', 191);
			$table->string('upload_path', 191);
			$table->string('type', 191);
			$table->string('tags', 191);
			$table->string('user_id', 191);
			$table->timestamps();
			$table->string('kinds', 191);
			$table->string('parent_model', 191);
			$table->integer('parent_model_id');
			$table->float('size', 10, 0);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('documents');
	}

}
