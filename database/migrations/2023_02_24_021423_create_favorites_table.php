<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFavoritesTable extends Migration {

	public function up()
	{
		Schema::create('favorites', function(Blueprint $table) {
			$table->id();
			$table->unsignedBigInteger('user_id');
			$table->unsignedBigInteger('partner_id');
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('favorites');
	}
}