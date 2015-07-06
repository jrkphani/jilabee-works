<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentHistoryTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::connection('jotterBase')->create('paymentHistory', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('customerId');
			$table->enum('type',array('card','cheque'));
			$table->string('invoiceNum')->nullable();
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
		Schema::connection('jotterBase')->drop('paymentHistory');
	}

}
