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
        Schema::create('vibetags', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->unsignedInteger('usage_count')->default(0);
            $table->timestamps();
        });

        Schema::create('snacc_vibetag', function (Blueprint $table) {
            $table->id();
            $table->foreignId('snacc_id')->constrained()->onDelete('cascade');
            $table->foreignId('vibetag_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            $table->unique(['snacc_id', 'vibetag_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('snacc_vibetag');
        Schema::dropIfExists('vibetags');
    }
};
