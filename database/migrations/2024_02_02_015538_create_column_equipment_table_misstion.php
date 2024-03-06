<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateColumnEquipmentTableMisstion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('missions', function (Blueprint $table) {
            $table->renameColumn('award', 'equipment_id');
            $table->bigInteger('gold')->after('award');
            $table->bigInteger('experience')->after('gold');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('missions', function (Blueprint $table) {
            $table->dropColumn('gold');
            $table->dropColumn('experience');
            $table->renameColumn('equipment_id', 'award');
        });
    }
}
