<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::connection('base')->create('clients', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('domain');
			$table->string('customerId',45);
			$table->string('dbIp',16);
			$table->string('dbUser',16);
			$table->string('dbPassword',16);
			$table->string('dbName',16);
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
		Schema::connection('base')->drop('clients');
	}

}
