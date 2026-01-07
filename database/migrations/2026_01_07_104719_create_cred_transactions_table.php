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
        Schema::create('cred_transactions', function (Blueprint $table) {
            $table->id();
            $table->ulid('slug')->unique();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('action'); // 'post_created', 'like_received', 'comment_received', 'quote_received', 'login_streak', 'milestone', 'viral_bonus', 'post_deleted', 'reported', 'warning', 'spam'
            $table->integer('amount'); // Can be positive or negative
            $table->text('description')->nullable();
            $table->morphs('source'); // Polymorphic: can be Snacc, Comment, etc. (automatically creates index)
            $table->timestamps();

            $table->index(['user_id', 'created_at']);
            $table->index(['action', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cred_transactions');
    }
};
