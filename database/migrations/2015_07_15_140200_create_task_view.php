<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTaskView extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//$col_need = "title,description,assignee,assigner,status,dueDate,created_by,updated_by,created_at,updated_at";
		DB::statement( "CREATE OR REPLACE VIEW tasks AS 
		(SELECT concat('task','','') as type,id,title,assigner,assignee,status,dueDate,concat(NULL,'',NULL) as minuteId from jobTasks)
		UNION
		(SELECT concat('minute','','') as type,minuteTasks.id,minuteTasks.title,minuteTasks.assigner,minuteTasks.assignee,
			minuteTasks.status,minuteTasks.dueDate,minuteTasks.minuteId from minuteTasks 
			JOIN minutes on minuteTasks.minuteId = minutes.id JOIN meetings on minutes.meetingId = meetings.id where meetings.approved = '1')");
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		 DB::statement( 'DROP VIEW Task' );
	}

}
