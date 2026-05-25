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
        Schema::create('health_alerts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shed_id')->constrained('sheds')->onDelete('cascade');
            $table->date('date_logged');
            $table->integer('daily_mortality_count')->default(0);
            $table->decimal('mortality_rate', 5, 2)->default(0.00);
            $table->enum('alert_level', ['normal', 'warning', 'critical'])->default('normal');
            $table->boolean('quarantine_triggered')->default(false);
            $table->boolean('vaccine_drop_scheduled')->default(false);
            $table->enum('status', ['active', 'resolved'])->default('active');
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('health_alerts');
    }
};
