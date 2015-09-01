<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTempMeetingTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('tempMeetings', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('title','64');
			$table->text('description');
			$table->string('venue','64')->nullable();
			$table->string('type','64');
			$table->string('purpose','256');
			$table->string('attendees','64')->nullable();
			$table->string('minuters','64');
			$table->dateTime('startDate');
			$table->dateTime('endDate');
			$table->binary('details');
			$table->integer('oid')->unsigned()->nullable();
			$table->enum('draft',array('0','1'))->default('1');
			$table->enum('approved',array('-1','0','1'))->default('0');
			$table->string('reason','64');
			$table->integer('created_by')->unsigned();
			$table->integer('updated_by')->unsigned();
        	$table->timestamps();
		});
		Schema::table('tempMeetings', function(Blueprint $table)
		{
			$table->foreign('created_by')->references('userId')->on('profiles')->onDelete('restrict')->onUpdate('cascade');
			$table->foreign('updated_by')->references('userId')->on('profiles')->onDelete('restrict')->onUpdate('cascade');
			$table->foreign('oid')->references('id')->on('organizations')->onDelete('restrict')->onUpdate('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//
	}

}
