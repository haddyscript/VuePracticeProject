<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('checkout', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->json('cart_id'); 
            $table->string('coupon_code')->nullable(); 
            // $table->integer('is_place_order')->default(0)->comment('0: no, 1: yes')->after('coupon_code'); FOR LOCAL
            $table->integer('is_place_order')->default(0)->comment('0: no, 1: yes');
            $table->timestamp('created_at')->useCurrent(); 
            $table->timestamp('updated_at')->nullable(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('checkout');
    }
};
