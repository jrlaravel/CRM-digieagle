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
        Schema::table('cv_details', function (Blueprint $table) {
            $table->enum('status', [
                'Selection', 
                'Phone Interview', 
                'Technical Interview', 
                'Practical Interview', 
                'Background Verification', 
                'Finalisation',
                'Rejected'
            ])->default('Selection')->after('cv_path'); // Adjust the position as needed
        });
    }
    
    public function down(): void
    {
        Schema::table('cv_details', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
    
};
