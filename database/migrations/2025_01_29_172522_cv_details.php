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
        Schema::create('cv_details', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->bigInteger('phone');
            $table->string('designation');
            $table->string('notice_period');
            $table->integer('experience');
            $table->integer('current_ctc')->nullable();
            $table->integer('expected_ctc')->nullable();
            $table->string('cv_path'); // Store CV file path
            $table->timestamps(); // Add timestamps for created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cv_details');
    }
};
