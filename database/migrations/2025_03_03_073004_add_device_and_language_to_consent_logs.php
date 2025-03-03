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
        Schema::table('consent_logs', function (Blueprint $table) {
            $table->string('device_type')->nullable()->after('user_agent');
            $table->string('language', 10)->nullable()->after('device_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('consent_logs', function (Blueprint $table) {
            $table->dropColumn(['device_type', 'language']);
        });
    }
};
