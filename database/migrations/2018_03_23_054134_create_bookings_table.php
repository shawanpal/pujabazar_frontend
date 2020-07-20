<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('sub_category_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('category_id')->references('id')->on('categories');
            $table->foreign('sub_category_id')->references('id')->on('sub_categories');
            $table->string('name');
            $table->string('location')->nullable();
            $table->string('language')->nullable();
            $table->string('enlisted_in')->nullable();
            $table->string('preferable_events')->nullable();
            $table->string('preferable_place')->nullable();
            $table->string('performane_duration')->nullable();
            $table->string('price');
            $table->string('performance_fee')->nullable();
            $table->string('video')->nullable();
            $table->string('on_stage_team')->nullable();
            $table->string('off_stage_team')->nullable();
            $table->enum('off_stage_food',[0, 1])->default(0);
            $table->text('details')->nullable();
            $table->enum('status',[0, 1])->default(0);
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
        Schema::dropIfExists('bookings');
    }
}
