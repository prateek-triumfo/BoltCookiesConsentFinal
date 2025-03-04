<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('domains', function (Blueprint $table) {
            if (!Schema::hasColumn('domains', 'api_key')) {
                $table->string('api_key')->unique()->nullable();
            }
            if (!Schema::hasColumn('domains', 'is_active')) {
                $table->boolean('is_active')->default(true);
            }
            if (!Schema::hasColumn('domains', 'banner_settings')) {
                $table->json('banner_settings')->nullable();
            }
            if (!Schema::hasColumn('domains', 'script_id')) {
                $table->string('script_id')->unique()->nullable();
            }
            if (!Schema::hasColumn('domains', 'last_used_at')) {
                $table->timestamp('last_used_at')->nullable();
            }
            if (!Schema::hasColumn('domains', 'consent_count')) {
                $table->integer('consent_count')->default(0);
            }
        });
    }

    public function down()
    {
        Schema::table('domains', function (Blueprint $table) {
            $table->dropColumn([
                'api_key',
                'is_active',
                'banner_settings',
                'script_id',
                'last_used_at',
                'consent_count'
            ]);
        });
    }
}; 