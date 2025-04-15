<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBusinessDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('business_details', function (Blueprint $table) {
            $table->id(); 
            $table->string('name');
            $table->text('description')->nullable();
            $table->date('founded_year')->nullable();
            $table->string('owner_name')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('email')->nullable();
            $table->string('website_url')->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('social_media_links')->nullable(); 
            $table->string('business_hours')->nullable();
            $table->string('logo_url')->nullable();
            $table->string('edited_by')->nullable();
            $table->string('contact_name')->nullable();
            $table->boolean('live')->default(true); 
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
        Schema::dropIfExists('business_details');
    }
}
