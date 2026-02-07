<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppointmentInvoicesTable extends Migration {

	public function up()
	{
		Schema::create('appointment_invoices', function(Blueprint $table) {
			$table->increments('id');
			$table->unsignedBigInteger('appointment_id');
			$table->string('payment_method')->nullable();
			$table->string('total_price')->nullable();
			$table->string('fixed_price')->nullable();
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('appointment_invoices');
	}
}