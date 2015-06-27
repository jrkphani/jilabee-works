<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;


class CreateProfilesTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('client')->create('profiles', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('userId')->unique()->unsigned();
            $table->string('name','64');
            $table->string('phone',16);
            $table->date('dob');
            $table->enum('gender', array('M','F','O'));
            $table->string('notification',16);
            $table->integer('created_by')->unsigned();
			$table->integer('updated_by')->unsigned();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('client')->drop('profiles');
    }

}