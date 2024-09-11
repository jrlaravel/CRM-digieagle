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
        Schema::create('project_detail', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description'); // Changed to 'text' for longer descriptions
            $table->unsignedBigInteger('project_type');
            $table->string('target_audience_age')->nullable();
            $table->string('target_city')->nullable();
            $table->json('platform');
            $table->date('start_date');
            $table->date('deadline');
            $table->enum('priority',['normal','urgent'])->default('normal');
            $table->integer('status');
            $table->timestamps();

            // Foreign key relationships
            $table->foreign('project_type')->references('id')->on('project_type')->onDelete('cascade');

            // Indexes for better query performance
            $table->index('project_type');
        });

        // Create pivot table for project-user many-to-many relationship
        Schema::create('project_user', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('project_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            // Foreign key relationships
            $table->foreign('project_id')->references('id')->on('project_detail')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            // Adding indexes for performance
            $table->index('project_id');
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_user'); // Drop pivot table first
        Schema::dropIfExists('project_detail'); // Then drop project table
    }
};
