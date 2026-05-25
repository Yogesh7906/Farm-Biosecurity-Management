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
        Schema::create('biosecurity_audits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('farm_id')->constrained('farms')->onDelete('cascade');
            $table->string('auditor_name');
            $table->date('audit_date');
            $table->boolean('cleaning_done')->default(false);
            $table->boolean('sanitation_zones_checked')->default(false);
            $table->boolean('boundary_checks_passed')->default(false);
            $table->integer('score')->default(0);
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('biosecurity_audits');
    }
};
