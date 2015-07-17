<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOtherTaskTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('otherTasks', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('taskId')->unsigned();
			$table->enum('type', array('task','minute'));
			$table->string('title','64');
			$table->text('description');
			$table->integer('assignee')->unsigned();
			$table->string('assigner','64');
			$table->enum('status', array('Draft','Sent','Rejected','Open','Completed' ,'Closed','Cancelled'))->default('Sent');
			$table->string('reason')->nullable();
			$table->dateTime('dueDate')->nullable();
			$table->integer('minuteId')->unsigned()->nullable();
			$table->string('org','64');
			$table->integer('created_by')->unsigned();
			$table->integer('updated_by')->unsigned();
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
		Schema::drop('otherTasks');
	}

}
