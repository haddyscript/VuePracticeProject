<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderBillingDetailsTable extends Migration
{
    public function up()
    {
        Schema::create('order_billing_details', function (Blueprint $table) {
            $table->id();
            $table->string('country', 100);
            $table->string('first_name', 100);
            $table->string('last_name', 100);
            $table->string('landmark_company_building', 255)->nullable();
            $table->text('address');
            $table->string('apartment_suite_unit', 255)->nullable();
            $table->string('state_country', 100);
            $table->string('postal_zip', 20);
            $table->string('email_address', 255);
            $table->string('phone', 20);
            $table->text('order_notes')->nullable();
            $table->string('coupon_code', 50)->nullable();
            $table->unsignedBigInteger('product_id');
            $table->json('product_details');
            $table->decimal('cart_subtotal', 10, 2);
            $table->decimal('discount_total', 10, 2);
            $table->decimal('order_total', 10, 2);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('order_billing_details');
    }
}