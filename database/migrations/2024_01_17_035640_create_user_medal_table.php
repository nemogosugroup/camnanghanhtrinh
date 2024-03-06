<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserMedalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_medals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('medal_id');
            $table->tinyInteger('status')->default(0);
            $table->timestamps();
            $table->foreign('medal_id')->references('id')->on('medals');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_medals');
    }
}
