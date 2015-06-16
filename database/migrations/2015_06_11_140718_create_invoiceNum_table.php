<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvoiceNumTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::connection('base')->create('invoice', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('customerId');
			$table->string('invoiceNum');
			$table->string('paymentId');
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
		Schema::connection('base')->drop('invoice');
	}

}
