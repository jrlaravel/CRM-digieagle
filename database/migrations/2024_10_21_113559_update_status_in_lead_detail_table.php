<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE lead_detail MODIFY COLUMN status ENUM('Not interested', 'Prospect', 'Lead', 'Hot Lead', 'Client', 'No Response')");
    }
    
    public function down(): void
    {
        DB::statement("ALTER TABLE lead_detail MODIFY COLUMN status ENUM('Not interested', 'Prospect', 'Lead', 'Hot Lead', 'Client')");
    }
    
};