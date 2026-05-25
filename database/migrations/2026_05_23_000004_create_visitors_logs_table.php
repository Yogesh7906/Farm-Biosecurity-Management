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
        Schema::create('visitors_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('farm_id')->constrained('farms')->onDelete('cascade');
            $table->string('name');
            $table->string('phone');
            $table->string('purpose');
            $table->decimal('temperature', 4, 2)->nullable();
            $table->boolean('visited_other_farm_past_48h')->default(false);
            $table->string('vehicle_plate')->nullable();
            $table->boolean('vehicle_sanitized')->default(false);
            $table->timestamp('check_in_time')->useCurrent();
            $table->timestamp('check_out_time')->nullable();
            $table->enum('status', ['quarantined', 'cleared'])->default('cleared');
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visitors_logs');
    }
};
