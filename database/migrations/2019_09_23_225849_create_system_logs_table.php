<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSystemLogsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('system_logs', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->integer('user_id');
			$table->string('action', 191);
			$table->string('category', 191);
			$table->timestamps();
			$table->integer('model_id')->nullable();
			$table->string('model')->nullable();
			$table->text('description', 65535)->nullable();
			$table->string('sub_model_class', 191)->nullable();
			$table->integer('sub_model_id')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('system_logs');
	}

}
