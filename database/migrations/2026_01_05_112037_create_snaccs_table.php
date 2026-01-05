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
        Schema::create('snaccs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('university_id')->constrained()->onDelete('cascade');
            $table->text('content')->nullable();
            $table->enum('visibility', ['campus', 'global'])->default('campus');
            $table->foreignId('quoted_snacc_id')->nullable()->constrained('snaccs')->onDelete('set null');
            $table->boolean('is_deleted')->default(false);
            $table->unsignedInteger('likes_count')->default(0);
            $table->unsignedInteger('comments_count')->default(0);
            $table->unsignedInteger('quotes_count')->default(0);
            $table->timestamps();

            $table->index(['university_id', 'visibility', 'created_at']);
            $table->index('quoted_snacc_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('snaccs');
    }
};
