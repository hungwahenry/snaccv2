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
            $table->fullText('content', 'snaccs_content_fulltext');
        });

        Schema::table('profiles', function (Blueprint $table) {
            $table->index('username', 'profiles_username_index');
            $table->fullText('bio', 'profiles_bio_fulltext');
        });

        Schema::table('vibetags', function (Blueprint $table) {
            $table->index('name', 'vibetags_name_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('snaccs', function (Blueprint $table) {
            $table->dropFullText('snaccs_content_fulltext');
        });

        Schema::table('profiles', function (Blueprint $table) {
            $table->dropIndex('profiles_username_index');
            $table->dropFullText('profiles_bio_fulltext');
        });

        Schema::table('vibetags', function (Blueprint $table) {
            $table->dropIndex('vibetags_name_index');
        });
    }
};
