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
        Schema::create('hosting_and_domain', function (Blueprint $table) {
            $table->id();
            $table->string('client_name');
            $table->string('logo')->nullable();
            $table->string('domain_name');
            $table->string('domain_purchase_form');
            $table->date('domain_purchase_date');
            $table->date('domain_expire_date');
            $table->integer('domain_amount');
            $table->string('domain_email')->nullable();
            $table->string('domain_id')->nullable();
            $table->string('domain_password');
            $table->string('hosting_purchase_from');
            $table->string('hosting_link');
            $table->integer('hosting_amount');
            $table->date('hosting_purchase_date');
            $table->date('hosting_expire_date');
            $table->string('hosting_email')->nullable();
            $table->string('hosting_id')->nullable();
            $table->string('hosting_password');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hosting_and_domain');
    }
};
