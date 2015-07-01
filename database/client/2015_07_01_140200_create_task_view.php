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
		$col_need = "title,description,assignee,assigner,status,dueDate,created_by,updated_by,created_at,updated_at";
		DB::statement( 'CREATE VIEW task AS
			(SELECT "'.$col_need.'" FROM `jobTasks`) union all
			(SELECT "'.$col_need.'" FROM `minuteTasks` )' );
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
