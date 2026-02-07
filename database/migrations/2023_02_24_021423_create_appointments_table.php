<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppointmentsTable extends Migration {

	public function up()
	{
		Schema::create('appointments', function(Blueprint $table) {
			$table->increments('id');
			$table->unsignedBigInteger('user_id');
			$table->unsignedBigInteger('partner_id');
			$table->unsignedBigInteger('service_id');
			$table->datetime('from_time')->nullable();
			$table->datetime('date_time')->nullable();
			$table->string('total_price')->nullable();
			$table->string('status')->nullable();
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('appointments');
	}
}