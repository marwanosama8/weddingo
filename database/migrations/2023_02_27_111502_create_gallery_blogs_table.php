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
        Schema::create('gallery_blogs', function (Blueprint $table) {
            $table->id();
            $table->string('partner_id')->nullable();
            $table->string('love_reaction')->nullable();
            $table->string('sad_reaction')->nullable();
            $table->string('angry_reaction')->nullable();
            $table->string('appreciate_reaction')->nullable();
            $table->string('comment')->nullable();
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
        Schema::dropIfExists('gallery_blogs');
    }
};
