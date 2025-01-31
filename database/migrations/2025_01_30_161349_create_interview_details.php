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
        Schema::create('interview_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('candidate_id');  // Foreign key reference for the candidate
            $table->string('interview_type');  // Interview type (e.g., Technical, HR, etc.)
            $table->date('interview_date');  // Interview date
            $table->time('interview_time');  // Interview time
            $table->timestamps();  // Created at & Updated at columns
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('interview_details');
    }
};
