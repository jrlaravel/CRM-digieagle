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
        Schema::create('lead_answer_detail', function (Blueprint $table) {
            $table->id();
            $table->UnsignedBigInteger('lead_id');
            $table->unsignedBigInteger('lead_question_id');
            $table->string('answer');
            $table->foreign('lead_id')->references('id')->on('lead_detail')->onDelete('cascade');
            $table->foreign('lead_question_id')->references('id')->on('lead_question')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lead_answer_detail');
    }
};
