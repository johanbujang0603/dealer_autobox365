<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateInventoriesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('inventories', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->timestamps();
			$table->integer('vehicle_type')->unsigned()->nullable();
			$table->string('make', 191)->nullable();
			$table->string('model', 191)->nullable();
			$table->string('generation', 191)->nullable();
			$table->string('serie', 191)->nullable();
			$table->string('trim', 191)->nullable();
			$table->string('equipment', 191)->nullable();
			$table->integer('year')->nullable()->default(0);
			$table->float('price', 10, 0)->nullable()->default(0);
			$table->integer('currency')->nullable();
			$table->enum('negotiable', array('Yes','No'))->nullable();
			$table->string('country', 191)->nullable();
			$table->string('city', 191)->nullable();
			$table->string('color', 191)->nullable();
			$table->string('transmission', 191)->nullable();
			$table->string('engine', 191)->nullable();
			$table->string('fuel_type', 191)->nullable();
			$table->string('body_type', 191)->nullable();
			$table->string('options', 191)->nullable();
			$table->string('location', 191)->nullable();
			$table->text('description', 65535)->nullable();
			$table->string('tags', 191)->nullable();
			$table->string('status', 191)->nullable();
			$table->integer('user_id')->default(0);
			$table->enum('finance', array('Yes','No'))->nullable();
			$table->integer('is_deleted')->nullable()->default(0);
			$table->integer('is_draft')->default(1);
			$table->float('mileage', 10, 0);
			$table->float('latitude', 10, 0)->default(0);
			$table->float('longitude', 10, 0)->default(0);
			$table->string('mileage_unit', 191)->default('km');
			$table->enum('steering_wheel', array('left','right'))->default('right');
			$table->string('vin', 191)->nullable();
			$table->string('plate_number', 191)->nullable();
			$table->string('unique_field', 191)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('inventories');
	}

}
