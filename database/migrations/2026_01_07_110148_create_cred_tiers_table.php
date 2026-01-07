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
        Schema::create('cred_tiers', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Newbie, Active, Regular, etc.
            $table->string('slug')->unique(); // newbie, active, regular, etc.
            $table->string('emoji')->nullable(); // Visual representation
            $table->string('color')->nullable(); // Hex color for UI (e.g., #10b981)
            $table->unsignedInteger('min_cred'); // Minimum cred to reach this tier
            $table->unsignedInteger('max_cred')->nullable(); // Maximum cred (null for highest tier)
            $table->text('description')->nullable();
            $table->unsignedInteger('order')->default(0); // Display order
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('min_cred');
            $table->index(['is_active', 'order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cred_tiers');
    }
};
