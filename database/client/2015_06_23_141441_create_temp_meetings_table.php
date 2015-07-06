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
		Schema::create('tempMeetings', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('title','64');
			$table->text('description');
			$table->string('venue','64')->nullable();
			$table->string('attendees','64');
			$table->string('minuters','64');
			$table->enum('status',array('Sent','Rejected'))->default('Sent');
			$table->string('reason','100')->nullable();
			$table->string('requested_by','20');
			$table->integer('created_by')->unsigned();
			$table->integer('updated_by')->unsigned();
        	$table->timestamps();
		});
		Schema::table('tempMeetings', function(Blueprint $table)
		{
			$table->foreign('created_by')->references('userId')->on('profiles')->onDelete('restrict')->onUpdate('cascade');
			$table->foreign('updated_by')->references('userId')->on('profiles')->onDelete('restrict')->onUpdate('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('tempMeetings');
	}

}
