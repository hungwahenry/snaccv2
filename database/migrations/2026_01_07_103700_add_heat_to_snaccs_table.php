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
            $table->unsignedInteger('heat_score')->default(0)->after('quotes_count');
            $table->timestamp('heat_peak_at')->nullable()->after('heat_score');
            $table->timestamp('heat_calculated_at')->nullable()->after('heat_peak_at');
            $table->unsignedInteger('views_count')->default(0)->after('heat_calculated_at');

            // Index for trending queries (campus-scoped heat + recent)
            $table->index(['university_id', 'heat_score', 'created_at']);
            $table->index('heat_calculated_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('snaccs', function (Blueprint $table) {
            $table->dropIndex(['university_id', 'heat_score', 'created_at']);
            $table->dropIndex(['heat_calculated_at']);

            $table->dropColumn([
                'heat_score',
                'heat_peak_at',
                'heat_calculated_at',
                'views_count',
            ]);
        });
    }
};
