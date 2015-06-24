<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTempMeetingsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::connection('client')->create('tempMeetings', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('title','64');
			$table->mediumText('description');
			$table->string('venue','64')->nullable();
			$table->string('attendees','64');
			$table->string('minuters','64');
			$table->enum('status',array('waiting','rejected'))->default('waiting');
			$table->string('reason','100')->nullable();
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
		Schema::connection('client')->drop('tempMeetings');
	}

}
