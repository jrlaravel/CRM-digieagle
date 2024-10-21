<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('lead_detail', function (Blueprint $table) {
            $table->string('lead_source')->nullable(); 
        });
    }

    public function down(): void
    {
        Schema::table('lead_detail', function (Blueprint $table) {
            $table->string('lead_source')->nullable(); 
        });
    }
};
