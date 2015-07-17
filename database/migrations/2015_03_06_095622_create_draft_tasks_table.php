<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDraftTasksTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('draftTasks', function(Blueprint $table)
		{
			$table->increments('id')->unique();
			$table->string('title','64')->nullable();
			$table->text('description')->nullable();
			$table->text('notes')->nullable();
			$table->string('assignee','64')->nullable();;
			$table->integer('assigner')->nullable();
			$table->string('orginator','64')->nullabel();
			$table->string('dueDate','32')->nullable();
			$table->integer('created_by')->unsigned();
        	$table->timestamps();
		});
		Schema::table('draftTasks', function(Blueprint $table)
		{		
			$table->foreign('created_by')->references('userId')->on('profiles')->onDelete('restrict')->onUpdate('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('draftTasks');
	}

}
