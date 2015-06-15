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
		Schema::connection('base')->create('organization', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('customerId')->unique();
			$table->string('email')->unique();
			$table->string('domain')->unique();
			$table->string('password');
			$table->tinyInteger('active')->default('0');
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
		Schema::drop('organization');
	}

}