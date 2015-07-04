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
		Schema::create('ideas', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('pId')->unsigned();
			$table->string('title','64');
			$table->text('description');
			$table->string('orginator','64')->nullabel();
			$table->integer('created_by')->unsigned();
			$table->integer('updated_by')->unsigned();
        	$table->timestamps();
        	$table->softDeletes();
		});
		Schema::table('ideas', function(Blueprint $table)
		{
			$table->foreign('pId')->references('id')->on('minutes')->onDelete('restrict')->onUpdate('cascade');
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
		Schema::drop('ideas');
	}

}
