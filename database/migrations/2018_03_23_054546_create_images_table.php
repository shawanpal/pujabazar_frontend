<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('images', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sub_category_id')->nullable();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->unsignedBigInteger('package_id')->nullable();
            $table->unsignedBigInteger('booking_id')->nullable();
            $table->foreign('sub_category_id')->references('id')->on('sub_categories');
            $table->foreign('product_id')->references('id')->on('products');
            $table->foreign('package_id')->references('id')->on('packages');
            $table->foreign('booking_id')->references('id')->on('bookings');
            $table->enum('main_image',[0, 1])->default(0);
            $table->string('image');
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
        Schema::dropIfExists('images');
    }
}
