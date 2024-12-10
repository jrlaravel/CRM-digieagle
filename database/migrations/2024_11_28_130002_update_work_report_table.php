<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class UpdateWorkReportTable extends Migration
{
    public function up()
    {
        Schema::table('work_report', function (Blueprint $table) {
            $table->date('report_date')->default(DB::raw('CURRENT_DATE'))->change();
        });
    }

    public function down()
    {
        Schema::table('work_report', function (Blueprint $table) {
            $table->date('report_date')->change(); // Remove the default
        });
    }
}
