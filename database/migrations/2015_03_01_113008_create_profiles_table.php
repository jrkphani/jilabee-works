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
        Schema::create('profiles', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('userId')->unique()->unsigned();
            $table->string('name','64');
            $table->string('phone',16);
            $table->date('dob');
            $table->enum('gender', array('M','F','O'));
            $table->integer('role')->default(1)->unsigned();
            $table->string('notification',16);
            $table->integer('created_by')->unsigned();
			$table->integer('updated_by')->unsigned();
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::table('profiles', function(Blueprint $table)
        {
            $table->foreign('created_by')->references('id')->on('users')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('role')->references('id')->on('roles')->onDelete('restrict')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('profiles');
    }

}