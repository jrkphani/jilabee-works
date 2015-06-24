<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDraftTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::connection('client')->create('draft', function(Blueprint $table)
		{
			$table->integer('parentId')->unsigned()->nullable();
			$table->string('title');
			$table->mediumText('description');
			$table->integer('assignee');
			$table->integer('assigner')->nullable();
			$table->string('orginator','64')->nullabel();
			$table->enum('type', array('job','minute','job_idea','minute_idea'))->default('job');
			$table->string('dueDate','32')->nullable();
			$table->integer('created_by')->unsigned();
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
		Schema::connection('client')->drop('draft');
	}

}
