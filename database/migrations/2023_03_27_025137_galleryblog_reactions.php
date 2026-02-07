<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('galleryblog_reactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('galleryblog_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('reaction_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('galleryblog_reactions');
    }
};
