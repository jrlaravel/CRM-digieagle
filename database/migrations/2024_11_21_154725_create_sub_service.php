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
        Schema::create('sub_service', function (Blueprint $table) {
            $table->id();
            $table->string('sub_service');
            $table->unsignedBigInteger('main_service_id'); // Ensure it matches the type in `main_service`
            $table->foreign('main_service_id')->references('id')->on('main_service')->onDelete('cascade');
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sub_service');
    }
};
