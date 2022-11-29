<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVnVendorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vn_vendors', function (Blueprint $table) {
            $table->id();
            $table->string('company_name')->nullable();
            $table->json('description')->nullable();
            $table->json('location')->nullable();
            $table->time('starting_time')->nullable();
            $table->time('closing_time')->nullable();
            $table->enum('status', ['1', '2']);
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('type_id')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->foreign('user_id')->references('id')->on('um_users');
            $table->foreign('created_by')->references('id')->on('um_users');
            $table->foreign('type_id')->references('id')->on('vn_types_of_vendors');
            $table->softDeletes();
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
        Schema::dropIfExists('vn_vendors');
    }
}
