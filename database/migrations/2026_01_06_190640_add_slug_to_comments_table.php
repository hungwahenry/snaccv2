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
        Schema::table('comments', function (Blueprint $table) {
            $table->string('slug', 26)->nullable()->after('id');
            $table->index('slug');
        });

        // Backfill slugs for existing records
        \App\Models\Comment::whereNull('slug')->each(function ($comment) {
            $comment->slug = (string) \Illuminate\Support\Str::ulid();
            $comment->save();
        });

        // Make slug unique and not nullable after backfill
        Schema::table('comments', function (Blueprint $table) {
            $table->string('slug', 26)->nullable(false)->unique()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('comments', function (Blueprint $table) {
            $table->dropIndex(['slug']);
            $table->dropColumn('slug');
        });
    }
};
