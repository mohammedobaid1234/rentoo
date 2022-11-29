<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVnTimeTypeOfVendorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vn_time_type_of_vendor', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('time_id')->nullable();
            $table->unsignedBigInteger('type_of_vendor')->nullable();
            $table->foreign('type_of_vendor')->references('id')->on('vn_types_of_vendors');
            $table->foreign('time_id')->references('id')->on('vn_time_label');
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
        Schema::dropIfExists('vn_time_type_of_vendor');
    }
}
