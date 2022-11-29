<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVnOffersProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vn_offers_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('offer_id')->nullable();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->foreign('offer_id')->references('id')->on('vn_offers');
            $table->foreign('product_id')->references('id')->on('pm_products');
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
        Schema::dropIfExists('vn_offers_products');
    }
}
