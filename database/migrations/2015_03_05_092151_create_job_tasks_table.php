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
			$table->string('title','64');
			$table->text('description');
			$table->text('notes')->nullable();
			$table->string('assignee','64');
			$table->integer('assigner')->nullable();
			$table->string('clientEmail','64')->nullable();
			$table->enum('status', array('Draft','Sent','Rejected','Open','Completed' ,'Closed','Cancelled'))->default('Sent');
			$table->string('reason')->nullable();
			$table->dateTime('dueDate')->nullable();
			$table->integer('created_by')->unsigned();
			$table->integer('updated_by')->unsigned();
        	$table->timestamps();
        	$table->softDeletes();
		});
		Schema::table('jobTasks', function(Blueprint $table)
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
		Schema::drop('jobTasks');
	}

}
