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
        Schema::table('follow_up', function (Blueprint $table) {
            $table->date('call_date')->nullable(); // Add call_date field
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('follow_up', function (Blueprint $table) {
            $table->dropColumn('call_date'); // Remove call_date field
        });
    }
};
