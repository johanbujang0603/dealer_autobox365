<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateLeadInterestsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('lead_interests', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->integer('lead_id');
			$table->string('item_option', 191)->nullable();
			$table->integer('inventory_id')->nullable();
			$table->integer('vehicle_type')->nullable();
			$table->integer('make')->nullable();
			$table->integer('model')->nullable();
			$table->integer('generation')->nullable();
			$table->integer('serie')->nullable();
			$table->integer('trim')->nullable();
			$table->integer('equipment')->nullable();
			$table->integer('transmission')->nullable();
			$table->string('color', 191)->nullable();
			$table->string('engine', 191)->nullable();
			$table->string('steering_whieel', 191)->nullable();
			$table->string('channel', 191)->nullable();
			$table->text('notes', 65535)->nullable();
			$table->timestamps();
			$table->float('mileage_from', 10, 0)->nullable();
			$table->float('mileage_to', 10, 0)->nullable();
			$table->string('mileage_unit', 191)->nullable();
			$table->float('price_from', 10, 0)->nullable();
			$table->float('price_to', 10, 0)->nullable();
			$table->integer('price_currency')->nullable();
			$table->float('looking_to', 10, 0)->nullable();
			$table->string('looking_to_option', 191)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('lead_interests');
	}

}
