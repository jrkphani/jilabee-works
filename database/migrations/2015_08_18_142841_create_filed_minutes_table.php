<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFiledMinutesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('filedMinutes', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('taskId')->unsigned();
			$table->integer('minuteId')->unsigned();
			$table->string('title','64');
			$table->text('description');
			$table->string('assignee','64');
			$table->integer('assigner')->nullable();
			$table->enum('status', array('Draft','Sent','Rejected','Open','Completed' ,'Closed','Cancelled'))->default('Sent');
			$table->dateTime('dueDate')->nullable();
        	$table->timestamps();
        	$table->softDeletes();
		});
		Schema::table('filedMinutes', function(Blueprint $table)
		{
			$table->foreign('taskId')->references('id')->on('minuteTasks')->onDelete('restrict')->onUpdate('cascade');
			$table->foreign('minuteId')->references('id')->on('minutes')->onDelete('restrict')->onUpdate('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('filedMeetings');
	}

}
