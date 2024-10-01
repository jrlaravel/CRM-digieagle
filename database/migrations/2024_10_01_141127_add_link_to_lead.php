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
        Schema::table('lead_detail', function (Blueprint $table) {
            $table->string('inslink');
            $table->string('facebooklink');
            $table->string('weblink');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lead_detail', function (Blueprint $table) {
            $table->string('inslink');
            $table->string('facebooklink');
            $table->string('weblink');
        });
    }
};
