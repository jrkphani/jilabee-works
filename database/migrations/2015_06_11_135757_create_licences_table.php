<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLicencesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::connection('jotterBase')->create('licences', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('licencesNum')->unique();
			$table->string('customerId',42);
			$table->string('userId',42)->unique();
			$table->string('expireDate');
			$table->integer('subscriptionType');
			$table->integer('created_by')->unsigned();
			$table->integer('updated_by')->unsigned();
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
		Schema::connection('jotterBase')->drop('licences');
	}

}
