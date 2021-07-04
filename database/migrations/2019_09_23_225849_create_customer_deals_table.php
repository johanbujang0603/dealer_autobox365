<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCustomerDealsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('customer_deals', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->integer('lead_id');
			$table->date('conversion_date');
			$table->integer('inventory_purchased');
			$table->float('purchased_price', 10, 0);
			$table->integer('purchased_currency');
			$table->integer('sales_user');
			$table->integer('sales_location');
			$table->text('notes', 65535)->nullable();
			$table->timestamps();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('customer_deals');
	}

}
