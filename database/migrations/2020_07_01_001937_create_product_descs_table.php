<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductDescsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_descs', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id')->nullable();
            $table->foreign('product_id')->references('id')->on('products');            
            $table->string('size')->nullable();
            $table->string('weight')->nullable();
            $table->string('weight_unit')->nullable();
            $table->string('size_unit')->nullable();
            $table->string('price')->nullable();
            $table->string('discount')->nullable();
            $table->string('stock')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_descs');
    }
}
