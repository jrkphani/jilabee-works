<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMinuteTaskTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('minuteTasks', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('minuteId')->unsigned();
			$table->string('title');
			$table->mediumText('description');
			$table->string('assignee','64');
			$table->integer('assigner')->nullable();
			$table->enum('status', array('waiting','rejected','open','finished' ,'close','expired','timeout','failed'))->default('waiting');
			$table->dateTime('dueDate')->nullable();
			$table->integer('created_by')->unsigned();
			$table->integer('updated_by')->unsigned();
        	$table->timestamps();
        	$table->softDeletes();
		});
		Schema::table('minuteTasks', function(Blueprint $table)
		{
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
		Schema::drop('minuteTasks');
	}

}
