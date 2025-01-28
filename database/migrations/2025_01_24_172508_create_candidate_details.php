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
        Schema::create('candidate_details', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('phone');
            $table->text('address')->nullable();
            $table->string('designation')->nullable();
            $table->integer('experience')->nullable();
            $table->string('reference_name')->nullable();
            $table->string('reference_phone')->nullable();
            $table->string('organization_name')->nullable();
            $table->string('position_name')->nullable();
            $table->integer('notice_period')->nullable();
            $table->date('expected_date')->nullable();
            $table->decimal('current_ctc', 10, 2)->nullable();
            $table->decimal('expected_ctc', 10, 2)->nullable();
            $table->text('strengths')->nullable();
            $table->text('weaknesses')->nullable();
            $table->text('career_goal')->nullable();
            $table->text('position_responsibilities')->nullable();
            $table->text('areas_of_expertise')->nullable();
            $table->text('improve_your_knowledge')->nullable();
            $table->text('service_are_we_providing')->nullable();
            $table->text('reason_for_leaving')->nullable();
            $table->text('reason_for_applying')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('candidate_details');
    }
};
