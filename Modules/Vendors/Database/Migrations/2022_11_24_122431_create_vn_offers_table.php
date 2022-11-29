<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVnOffersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vn_offers', function (Blueprint $table) {
            $table->id();
            $table->json('name')->nullable();
            $table->json('description')->nullable();
            $table->float('value')->nullable();
            $table->date('starting_data');
            $table->date('ended_data');
            $table->unsignedBigInteger('vendor_id')->nullable();
            // 1=>active, 2=>deactivate
            $table->enum('status', ['1','2'])->default('1');
            $table->unsignedBigInteger('type_id')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->foreign('vendor_id')->references('id')->on('vn_vendors');
            $table->foreign('created_by')->references('id')->on('um_users');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vn_offers');
    }
}
