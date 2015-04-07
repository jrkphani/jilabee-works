<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotesDraftTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('notes_draft', function(Blueprint $table)
		{
			$table->integer('mhid')->unsigned();
			$table->string('title');
			$table->mediumText('description');
			$table->string('assignee','64')->nullable();
			$table->string('assigner','64')->nullable();
			//$table->enum('priority', array('immediate','high', 'normal','low'))->default('normal');
			$table->string('due','32')->nullable();
			$table->integer('created_by')->unsigned();
        	$table->timestamps();
		});
		Schema::table('notes_draft', function(Blueprint $table)
		{
			$table->foreign('mhid')->references('id')->on('minutes_history')->onDelete('restrict')->onUpdate('cascade');
			$table->foreign('created_by')->references('id')->on('users')->onDelete('restrict')->onUpdate('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('notes_draft');
	}

}
