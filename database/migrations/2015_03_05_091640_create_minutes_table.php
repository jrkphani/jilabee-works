<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMinutesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('minutes', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('meetingId')->unsigned();
			$table->string('venue','64')->nullable();
			$table->dateTime('startDate');
			$table->dateTime('endDate');
			$table->string('attendees','64');
			$table->string('absentees','64')->nullable();
			//$table->integer('lock_flag')->nullable()->unsigned()->default(0);
			$table->enum('field',['0','1'])->default(0);
			$table->integer('created_by')->unsigned();
			$table->integer('updated_by')->unsigned();
        	$table->timestamps();
        	$table->softDeletes();
		});
		Schema::table('minutes', function(Blueprint $table)
		{
			$table->foreign('meetingId')->references('id')->on('meetings')->onDelete('restrict')->onUpdate('cascade');
			$table->foreign('created_by')->references('userId')->on('profiles')->onDelete('restrict')->onUpdate('cascade');
			$table->foreign('updated_by')->references('userId')->on('profiles')->onDelete('restrict')->onUpdate('cascade');
			//$table->foreign('lock_flag')->references('userId')->on('profiles')->onDelete('restrict')->onUpdate('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('minutes');
	}

}
