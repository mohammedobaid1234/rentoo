<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddVerifiedToUmUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('um_users', function (Blueprint $table) {
            $table->smallInteger('verified')->after('password')->nullable();
            $table->enum('status', ['active', 'not_active'])->after('verified')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('um_users', function (Blueprint $table) {
            $table->dropColumn('verified');
            $table->dropColumn('status');
        });
    }
}
