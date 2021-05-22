<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->bigInteger('order_id')->default(0)->nullable();
            $table->enum('pin_type_id', ['sale','not_home','no','already_bought','delivery_pending']);
            $table->enum('payment_type', ['cash','cheque','online','none','paymentDue','webPayment']);
            $table->string('customer_name')->nullable();
            $table->string('full_address')->nullable();
            $table->datetime('order_date')->nullable();
            $table->double('price','5','2')->nullable();
            $table->integer('quantity')->nullable();
            $table->string('product_name')->nullable();
            $table->double('final_price','5','2')->nullable();
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
        Schema::dropIfExists('orders');
    }
}
