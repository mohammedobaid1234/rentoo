<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePmProductAttributesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pm_product_attribute_types', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->json('name');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('pm_product_category_attribute_types', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('attribute_type_id');

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('category_id')->references('id')->on('vn_types_of_vendors');
            $table->foreign('attribute_type_id')->references('id')->on('pm_product_attribute_types');
        });

        Schema::create('pm_product_attributes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('product_id')->nullable();
            $table->unsignedBigInteger('type_id')->nullable();
            $table->string('value')->nullable();
           
            $table->unsignedBigInteger('created_by')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('product_id')->references('id')->on('pm_Products');
            $table->foreign('type_id')->references('id')->on('pm_product_attribute_types');
            $table->foreign('created_by')->references('id')->on('um_users');
        });

        Schema::create('pm_product_attribute_type_values', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('attribute_type_id');
            $table->string('name');
            $table->unsignedBigInteger('created_by')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('attribute_type_id')->references('id')->on('pm_product_attribute_types');
            $table->foreign('created_by')->references('id')->on('um_users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pm_product_attribute_type_values');
        Schema::dropIfExists('pm_product_attributes');
        Schema::dropIfExists('pm_product_category_attribute_types');
        Schema::dropIfExists('pm_product_attribute_types');
    }
}
