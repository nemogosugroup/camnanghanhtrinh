<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewColumnTableUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('achievements')->nullable()->after('birthday');
            $table->bigInteger('experience')->default(0)->after('achievements');
            $table->bigInteger('gold')->default(0)->after('experience');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('achievements');
            $table->dropColumn('experience');
            $table->dropColumn('gold');
        });
    }
}
