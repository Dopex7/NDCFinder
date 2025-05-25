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
        Schema::create('ndc_codes', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('brand_name')->nullable();  
            $table->string('ndc_code')->unique();
            $table->string('generic_name')->nullable();
            $table->string('labeler_name')->nullable();
            $table->string('product_type')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ndc_codes');
    }
};
