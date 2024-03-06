<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToMissionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('missions', function (Blueprint $table) {            
            $table->string('link')->after('description')->nullable();
            $table->string('method')->after('link')->nullable();
            $table->string('award')->after('method')->nullable();
            $table->unsignedBigInteger('level')->after('award')->nullable();            
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
            $table->dropColumn('link');
            $table->dropColumn('method');
            $table->dropColumn('award');
            $table->dropColumn('level');
        });
    }
}
