<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePmCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pm_categories', function (Blueprint $table) {
            $table->id();
            $table->json('name')->nullable();
            $table->json('description')->nullable();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->unsignedBigInteger('vendor_type')->nullable();
            $table->enum('status', ['1', '2']);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->foreign('created_by')->references('id')->on('um_users');
            $table->foreign('parent_id')->references('id')->on('pm_categories');
            $table->foreign('vendor_type')->references('id')->on('vn_types_of_vendors');
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
        Schema::dropIfExists('pm_categories');
    }
}
