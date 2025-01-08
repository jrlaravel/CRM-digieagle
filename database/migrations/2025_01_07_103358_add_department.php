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
        Schema::table('work_report_detail', function (Blueprint $table) {
            // Drop the foreign key for 'service_id'
            $table->dropForeign(['service_id']);
            // Optionally drop the 'service_id' column
            $table->dropColumn('service_id');
        });

        Schema::table('company_services', function (Blueprint $table) {
            if (Schema::hasColumn('company_services', 'service_id')) {
                $table->dropForeign(['service_id']);
                $table->dropColumn('service_id');
            }
        });

        Schema::drop('sub_service');
        Schema::drop('main_service');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('work_report_detail', function (Blueprint $table) {
            $table->unsignedBigInteger('service_id');
            $table->foreign('service_id')->references('id')->on('sub_service')->onDelete('cascade');
        });

        Schema::table('company_services', function (Blueprint $table) {
            if (Schema::hasColumn('company_services', 'service_id')) {
                $table->dropForeign(['service_id']);
                $table->dropColumn('service_id');
            }
        });

        Schema::drop('sub_service');
        Schema::drop('main_service');
    }
};
