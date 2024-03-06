<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewColumnUser extends Migration
{
     /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('profile_id')->after('id')->nullable();
            $table->integer('employee_id')->after('profile_id')->nullable();           
            $table->string('first_name')->after('employee_id');
            $table->string('last_name')->after('first_name');
            $table->string('avatar')->after('password')->nullable();
            $table->tinyInteger('gender')->after('avatar')->default(0);
            $table->string('dept')->after('gender')->nullable();
            $table->string('department')->after('dept')->nullable();
            $table->string('area')->after('department')->nullable();
            $table->string('job')->after('area')->nullable();
            $table->string('phone')->after('job')->nullable();
            $table->dateTime('birthday')->after('phone')->nullable();
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
            $table->dropColumn('profile_id');
            $table->dropColumn('employee_id');           
            $table->dropColumn('first_name');
            $table->dropColumn('last_name');
            $table->dropColumn('avatar');
            $table->dropColumn('gender');
            $table->dropColumn('dept');
            $table->dropColumn('department');
            $table->dropColumn('area');
            $table->dropColumn('job');
            $table->dropColumn('phone');
            $table->dropColumn('birthday');
        });
    }
}
