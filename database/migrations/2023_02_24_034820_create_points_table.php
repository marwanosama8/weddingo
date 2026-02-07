<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePointsTable extends Migration {

	public function up()
	{
		Schema::create('points', function(Blueprint $table) {
			$table->increments('id');
			$table->unsignedBigInteger('user_id');
			$table->integer('points')->nullable();
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('points');
	}
}