<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('comments', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('nid')->unsigned();
			$table->mediumText('description');
			$table->integer('created_by')->unsigned();
			$table->integer('updated_by')->unsigned();
        	$table->timestamps();
        	$table->softDeletes();
		});
		Schema::table('comments', function(Blueprint $table)
		{
			$table->foreign('nid')->references('id')->on('tasks')->onDelete('restrict')->onUpdate('cascade');
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
		Schema::drop('notes_history');
	}

}
