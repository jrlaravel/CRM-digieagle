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
        Schema::create('lead_detail', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('company_name')->nullable();
            $table->string('description')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->string('email')->nullable();
            $table->string('phone');
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->enum('status',['Not interested','Prospect','lead','hot lead','client']);
            $table->string('address')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lead_detail');
    }
};
