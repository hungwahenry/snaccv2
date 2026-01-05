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
        Schema::create('snacc_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('snacc_id')->constrained()->onDelete('cascade');
            $table->string('image_path');
            $table->unsignedTinyInteger('order')->default(0);
            $table->timestamps();

            $table->index(['snacc_id', 'order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('snacc_images');
    }
};
