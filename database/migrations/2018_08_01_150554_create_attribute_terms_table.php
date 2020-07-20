<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttributeTermsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attribute_terms', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id')->nullable();
            $table->unsignedBigInteger('package_id')->nullable();
            $table->unsignedBigInteger('booking_id')->nullable();
            $table->unsignedBigInteger('attribute_id');
            $table->unsignedBigInteger('term_id');
            $table->foreign('package_id')->references('id')->on('packages');
            $table->foreign('product_id')->references('id')->on('products');
            $table->foreign('booking_id')->references('id')->on('bookings');
            $table->foreign('attribute_id')->references('id')->on('attributes');
            $table->foreign('term_id')->references('id')->on('terms');
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
        Schema::dropIfExists('attribute_terms');
    }
}
