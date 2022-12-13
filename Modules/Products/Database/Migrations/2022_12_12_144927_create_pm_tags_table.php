<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePmTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pm_tags', function (Blueprint $table) {
            $table->id();
            $table->json('name')->nullable();
            $table->unsignedBigInteger('vendor_type_id')->nullable();
            $table->foreign('vendor_type_id')->references('id')->on('vn_types_of_vendors');
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
        Schema::dropIfExists('pm_tags');
    }
}
