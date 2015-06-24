<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobTasksTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('jobTasks', function(Blueprint $table)
		{
			$table->increments('id');
			//$table->integer('meetingId')->unsigned();
			$table->string('title');
			$table->mediumText('description');
			$table->string('assignee','64');
			$table->integer('assigner')->nullable();
			$table->enum('status', array('waiting','rejected','open','finished' ,'close','expired','timeout','failed'))->default('waiting');
			//$table->enum('priority', array('immediate','high', 'normal','low'))->default('normal');
			$table->dateTime('dueDate')->nullable();
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
		Schema::drop('jobTasks');
	}

}
