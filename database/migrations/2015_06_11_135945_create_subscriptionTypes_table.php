<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubscriptionTypesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::connection('base')->create('subscriptionTypes', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('module',16);
			$table->string('action',16);
			$table->string('monthlyPrice',5);
			$table->string('anualPrice',5);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::connection('base')->drop('ideas');
	}

}
