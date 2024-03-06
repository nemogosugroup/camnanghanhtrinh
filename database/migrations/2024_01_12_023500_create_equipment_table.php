<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEquipmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('equipment', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('type_equipment_id')->nullable();
            $table->unsignedBigInteger('medal_id')->nullable();
            $table->string('title');
            $table->string('slug');
            $table->text('description_designer')->nullable();
            $table->text('description_in_game')->nullable();
            $table->string('method')->nullable();
            $table->string('url_image')->nullable();
            $table->timestamps();
            $table->foreign('type_equipment_id')->references('id')->on('type_equipment')->onDelete('set null');
            $table->foreign('medal_id')->references('id')->on('medals')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('equipment');
    }
}
