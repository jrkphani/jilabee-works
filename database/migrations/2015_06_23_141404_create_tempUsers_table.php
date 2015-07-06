<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTempUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::connection('jotterBase')->create('tempUsers', function(Blueprint $table)
		{
			$table->increments('id')->unique();
			$table->string('userId',42)->unique();
			$table->string('email')->unique();
			$table->string('password', 60);
			$table->string('hash', 60);
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
		Schema::connection('jotterBase')->drop('users');
	}

}