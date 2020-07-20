<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');

            $table->string('invoice_id')->nullable();
            $table->string('payment_id')->nullable();
            $table->mediumText('cart')->nullable();
            $table->mediumText('package_item')->nullable();
            $table->string('create_at')->nullable();
            $table->string('delivery_time')->nullable();

            $table->enum('payment_status',['Pending', 'Completed'])->default('Pending');
            $table->enum('shipping_status',['Pending', 'Shipping', 'Completed'])->default('Pending');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
