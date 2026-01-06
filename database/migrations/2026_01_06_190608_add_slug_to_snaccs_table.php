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
        Schema::table('snaccs', function (Blueprint $table) {
            $table->string('slug', 26)->nullable()->after('id');
            $table->index('slug');
        });

        // Backfill slugs for existing records
        \App\Models\Snacc::whereNull('slug')->each(function ($snacc) {
            $snacc->slug = (string) \Illuminate\Support\Str::ulid();
            $snacc->save();
        });

        // Make slug unique and not nullable after backfill
        Schema::table('snaccs', function (Blueprint $table) {
            $table->string('slug', 26)->nullable(false)->unique()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('snaccs', function (Blueprint $table) {
            $table->dropIndex(['slug']);
            $table->dropColumn('slug');
        });
    }
};
