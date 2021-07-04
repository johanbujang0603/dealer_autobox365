<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCalendarTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('calendar', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->dateTime('datetime');
			$table->string('user_id', 191);
			$table->timestamps();
			$table->string('kinds', 191);
			$table->string('model_class', 191);
			$table->string('model_id', 191);
			$table->text('notes', 65535);
			$table->integer('create_user_id');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('calendar');
	}

}
