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
        Schema::create('client_meeting_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('lead_id');
            $table->string('description');
            $table->date('meeting_date'); // Fixed: Added column name for date
            $table->time('start_time');
            $table->foreign('lead_id')->references('id')->on('lead_detail')->onDelete('cascade'); // Fixed: Ensure the correct table name
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_meeting_details');
    }
};
