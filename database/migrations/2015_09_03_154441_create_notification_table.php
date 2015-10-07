<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotificationTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		 Schema::create('notifications', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('userId')->unsigned();
            $table->integer('objectId')->unsigned();
            $table->integer('parentId')->unsigned()->nullable();
            $table->string('objectType', 128);
            $table->string('subject', 128)->nullable();
            $table->text('body')->nullable();
            $table->string('tab',8)->nullable();
            $table->boolean('isRead')->default(0);
            $table->timestamps();
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('notifications');
	}

}
