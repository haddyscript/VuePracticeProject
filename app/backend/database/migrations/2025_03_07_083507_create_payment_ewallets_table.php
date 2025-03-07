<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('payment_ewallets', function (Blueprint $table) {
            $table->id();
            $table->string('external_id')->unique();
            $table->string('reference_id');
            $table->string('email')->nullable();
            $table->decimal('amount', 10, 2);
            $table->string('ewallet_type');
            $table->string('status');
            $table->string('redirect_url')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('payment_ewallets');
    }
};
