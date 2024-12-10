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
        Schema::create('work_report_detail', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id'); // Changed to unsignedBigInteger
            $table->unsignedBigInteger('service_id'); // Changed to unsignedBigInteger
            $table->unsignedBigInteger('date_id');
            $table->string('status');
            $table->time('start_time');
            $table->time('end_time');
            $table->time('total_time');
            $table->timestamps();
            
            // Foreign keys with cascade delete
            $table->foreign('company_id')->references('id')->on('company_detail')->onDelete('cascade');
            $table->foreign('service_id')->references('id')->on('sub_service')->onDelete('cascade');
            $table->foreign('date_id')->references('id')->on('work_report')->onDelete('cascade');

        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_report');
    }
};
