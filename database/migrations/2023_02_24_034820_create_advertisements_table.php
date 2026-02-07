<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdvertisementsTable extends Migration {

	public function up()
	{
		Schema::create('advertisements', function(Blueprint $table) {
			$table->increments('id');
			$table->string('page_name')->nullable();
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('advertisements');
	}
}