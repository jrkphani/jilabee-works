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
		Schema::create('draft', function(Blueprint $table)
		{
			$table->integer('parentId')->unsigned()->nullable();
			$table->string('title');
			$table->mediumText('description');
			$table->string('assignee','64')->nullable();
			$table->string('assigner','64')->nullable();
			$table->string('orginator','64')->nullabel();
			$table->enum('type', array('job','minute','job_idea','minute_idea'))->default('job');
			$table->string('dueDate','32')->nullable();
			$table->integer('created_by')->unsigned();
        	$table->timestamps();
		});
		// Schema::table('draft', function(Blueprint $table)
		// {
		// 	$table->foreign('created_by')->references('id')->on('users')->onDelete('restrict')->onUpdate('cascade');
		// });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('draft');
	}

}
