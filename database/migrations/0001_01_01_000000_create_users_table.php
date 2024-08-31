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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('username');
            $table->date('birth_date');
            $table->string('empcode');
            $table->enum('role',['employee','admin'])->default('employee');
            $table->string('email');
            $table->string('phone');
            $table->json('skills')->nullable();
            $table->Integer('department');
            $table->Integer('designation');
            $table->string('address');
            $table->string('password');
            $table->string('profile_photo_path')->nullable();
            $table->rememberToken();
            $table->timestamps();
            // $table->foreign('department')->references('id')->on('department')->onDelete('cascade');
            // $table->foreign('designation')->references('id')->on('designation')->onDelete('cascade');         
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email');
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id');
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
