

<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrganizationInfoTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::connection('base')->create('organizationInfo', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('customerId');
			$table->string('orgName',32);
			//$table->string('tagLine',64);
			$table->string('address',255);
			$table->string('phone',16);
			$table->string('phone1',16)->nullable();
			$table->string('licenses');
			$table->integer('created_by')->nullable();
			$table->integer('updated_by')->nullable();
			$table->timestamps();
		});
		Schema::connection('base')->table('organizationInfo', function(Blueprint $table)
		{
			$table->foreign('customerId')->references('customerId')->on('organization')->onDelete('restrict')->onUpdate('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('organizationInfo');
	}

}
