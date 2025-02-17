<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCouponsTable extends Migration
{
    public function up()
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); 
            $table->enum('discount_type', ['percentage', 'fixed']);
            $table->decimal('discount_value', 8, 2); 
            $table->decimal('min_order_amount', 8, 2)->default(0); 
            $table->date('start_date');
            $table->date('expiry_date'); 
            $table->integer('usage_limit')->default(0); 
            $table->integer('used_count')->default(0); 
            $table->boolean('is_active')->default(true); 
            $table->timestamps(); 
        });
    }

    public function down()
    {
        Schema::dropIfExists('coupons');
    }
}
