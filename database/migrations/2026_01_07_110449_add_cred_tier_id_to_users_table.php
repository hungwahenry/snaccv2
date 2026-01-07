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
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('cred_tier_id')->nullable()->after('daily_cred_reset_date')->constrained('cred_tiers')->onDelete('set null');
            $table->index('cred_tier_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['cred_tier_id']);
            $table->dropIndex(['cred_tier_id']);
            $table->dropColumn('cred_tier_id');
        });
    }
};
