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
        Schema::table('notifications', function (Blueprint $table) {
            // Indexed column for fast grouping lookups (replaces JSON_EXTRACT queries)
            // Format: "{type}:{source_type}:{source_id}" e.g., "like:Snacc:123"
            $table->string('notification_group_key')->nullable()->index()->after('data');
            
            // Track total actor count for display
            $table->unsignedInteger('actor_count')->default(1)->after('notification_group_key');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropColumn(['notification_group_key', 'actor_count']);
        });
    }
};
