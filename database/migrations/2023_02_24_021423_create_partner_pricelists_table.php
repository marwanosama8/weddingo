<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePartnerPricelistsTable extends Migration {

	public function up()
	{
		Schema::create('partner_pricelists', function(Blueprint $table) {
			$table->increments('id');
			$table->unsignedBigInteger('partner_id');
			$table->string('service')->nullable();
			$table->string('price')->nullable();
		});
	}

	public function down()
	{
		Schema::drop('partner_pricelists');
	}
}