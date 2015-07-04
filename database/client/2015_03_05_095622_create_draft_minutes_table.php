<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDraftMinutesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('draftMinutes', function(Blueprint $table)
		{
			$table->integer('minuteId')->unsigned()->nullable();
			$table->string('title','64');
			$table->text('description');
			$table->integer('assignee')->nullable();;
			$table->integer('assigner')->nullable();
			$table->string('orginator','64')->nullabel();
			$table->enum('type', array('task','idea'))->default('task');
			$table->string('dueDate','32')->nullable();
			$table->integer('created_by')->unsigned();
        	$table->timestamps();
		});
		Schema::table('draftMinutes', function(Blueprint $table)
		{
			$table->foreign('minuteId')->references('id')->on('minutes')->onDelete('restrict')->onUpdate('cascade');
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
		Schema::connection('client')->drop('draftMinutes');
	}

}
