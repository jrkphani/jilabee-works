<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::connection('jotterBase')->create('users', function(Blueprint $table)
		{
			$table->increments('id')->unique();
			$table->string('userId',42)->unique();
			$table->string('email')->unique();
			$table->string('password', 60);
			$table->enum('isAdmin',array('0','1'))->default('0');
			$table->enum('active',array('0','1'))->default('0');
			$table->rememberToken();
			$table->timestamps();
			$table->softDeletes();
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
