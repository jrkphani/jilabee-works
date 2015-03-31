<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('notes', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('mhid')->unsigned();
			$table->string('title');
			$table->mediumText('description');
			$table->string('assignee','64');
			$table->string('assigner','64')->nullable();
			$table->enum('status', array('waiting','rejected','open', 'close','expired','timeout','failed'))->default('waiting');
			//$table->enum('priority', array('immediate','high', 'normal','low'))->default('normal');
			$table->dateTime('due')->nullable();
			$table->integer('created_by')->unsigned();
			$table->integer('updated_by')->unsigned();
        	$table->timestamps();
        	$table->softDeletes();
		});
		Schema::table('notes', function(Blueprint $table)
		{
			$table->foreign('mhid')->references('id')->on('minutes_history')->onDelete('restrict')->onUpdate('cascade');			
			$table->foreign('created_by')->references('id')->on('users')->onDelete('restrict')->onUpdate('cascade');
			$table->foreign('updated_by')->references('id')->on('users')->onDelete('restrict')->onUpdate('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('notes');
	}

}
