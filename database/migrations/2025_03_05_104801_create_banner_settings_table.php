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
        Schema::create('banner_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('domain_id')->constrained()->onDelete('cascade');
            $table->string('banner_title');
            $table->text('banner_description');
            $table->string('accept_all_text', 50)->default('Accept All');
            $table->string('reject_all_text', 50)->default('Reject All');
            $table->string('manage_settings_text', 50)->default('Manage Settings');
            $table->string('save_preferences_text', 50)->default('Save Preferences');
            $table->string('cancel_text', 50)->default('Cancel');
            $table->string('primary_color', 7)->default('#4F46E5');
            $table->string('accept_color', 7)->default('#10B981');
            $table->string('reject_color', 7)->default('#EF4444');
            $table->string('background_color', 7)->default('#FFFFFF');
            $table->string('text_color', 7)->default('#000000');
            $table->string('font_family', 50)->default('Arial');
            $table->integer('font_size')->default(14);
            $table->boolean('show_manage_button')->default(true);
            $table->string('manage_button_position', 20)->default('bottom-right');
            $table->boolean('show_necessary_cookies')->default(true);
            $table->boolean('show_statistics_cookies')->default(true);
            $table->boolean('show_marketing_cookies')->default(true);
            $table->boolean('show_preferences_cookies')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banner_settings');
    }
};
