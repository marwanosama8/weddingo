<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePartnersTable extends Migration {

	public function up()
	{
		Schema::create('partners', function(Blueprint $table) {
			$table->increments('id');
			$table->unsignedBigInteger('user_id');
			$table->unsignedBigInteger('category_id')->nullable();
			$table->unsignedBigInteger('other_categroy_id')->nullable();
			$table->double('rate')->nullable()->default(0);
			$table->integer('gallery_limit')->nullable()->default(6);
			$table->boolean('active')->nullable()->default(0);
			$table->string('business_name')->nullable();
			$table->string('social_provider')->nullable();
			$table->string('social_url')->nullable();
			$table->string('business_type')->nullable();
			$table->string('about_us_survey')->nullable();
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('partners');
	}
}