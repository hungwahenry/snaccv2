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
            $table->unsignedInteger('cred_score')->default(0)->after('remember_token');
            $table->unsignedInteger('login_streak')->default(0)->after('cred_score');
            $table->date('last_login_date')->nullable()->after('login_streak');
            $table->unsignedInteger('daily_cred_earned')->default(0)->after('last_login_date');
            $table->date('daily_cred_reset_date')->nullable()->after('daily_cred_earned');

            $table->index('cred_score');
            $table->index(['login_streak', 'last_login_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['cred_score']);
            $table->dropIndex(['login_streak', 'last_login_date']);

            $table->dropColumn([
                'cred_score',
                'login_streak',
                'last_login_date',
                'daily_cred_earned',
                'daily_cred_reset_date',
            ]);
        });
    }
};
