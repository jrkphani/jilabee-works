<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobTaskCommentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::connection('client')->create('jobTaskComments', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('pId')->unsigned();
			$table->mediumText('description');
			$table->integer('created_by')->unsigned();
			$table->integer('updated_by')->unsigned();
        	$table->timestamps();
        	$table->softDeletes();
		});
		Schema::connection('client')->table('jobTaskComments', function(Blueprint $table)
		{
			$table->foreign('pId')->references('id')->on('jobTasks')->onDelete('restrict')->onUpdate('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::connection('client')->drop('comments');
	}

}
