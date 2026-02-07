<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReviewsTable extends Migration {

	public function up()
	{
		Schema::create('reviews', function(Blueprint $table) {
			$table->increments('id');
			$table->unsignedBigInteger('partner_id');
			$table->double('rate')->nullable();
			$table->string('review')->nullable();
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('reviews');
	}
}