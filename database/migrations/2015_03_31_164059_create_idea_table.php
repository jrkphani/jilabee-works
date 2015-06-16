<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIdeaTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::connection('client')->create('ideas', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('parentId')->unsigned();
			$table->string('title');
			$table->mediumText('description');
			$table->string('orginator','64')->nullabel();
			$table->enum('type', array('job','minute'))->default('job');
			$table->integer('created_by')->unsigned();
			$table->integer('updated_by')->unsigned();
        	$table->timestamps();
        	$table->softDeletes();
		});
		Schema::connection('client')->table('ideas', function(Blueprint $table)
		{
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
		Schema::connection('client')->drop('ideas');
	}

}
