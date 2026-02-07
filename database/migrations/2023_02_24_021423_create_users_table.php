<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration {

	public function up()
	{
		Schema::create('users', function(Blueprint $table) {
			$table->increments('id');
			$table->string('first_name')->nullable();
			$table->string('last_name')->nullable();
			$table->string('phone')->nullable();
			$table->string('email')->nullable();
			$table->string('gender')->nullable();
			$table->date('birth_date')->nullable();
			$table->string('password')->nullable();
			$table->string('provider_name')->nullable();
			$table->string('provider_id')->nullable();
			$table->unsignedBigInteger('country_id')->nullable();
			$table->unsignedBigInteger('city_id')->nullable();
			$table->boolean('is_blocked')->nullable()->default(0);
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('users');
	}
}