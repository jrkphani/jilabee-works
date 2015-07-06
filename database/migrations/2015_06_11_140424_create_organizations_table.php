<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrganizationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::connection('jotterBase')->create('organizations', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('customerId')->unique();
			$table->string('email')->unique();
			$table->string('secondEmail');
			$table->string('domain')->unique();
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
		Schema::connection('jotterBase')->drop('organizations');
	}

}