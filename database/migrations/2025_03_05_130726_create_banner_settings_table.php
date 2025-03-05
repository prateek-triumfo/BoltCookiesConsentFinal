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
            $table->string('primary_color');
            $table->string('text_color');
            $table->string('accept_color');
            $table->string('reject_color');
            $table->string('manage_color');
            $table->string('save_color');
            $table->string('cancel_color');
            $table->string('font_family');
            $table->string('font_size');
            $table->string('accept_all_text');
            $table->string('reject_all_text');
            $table->string('manage_settings_text');
            $table->string('save_preferences_text');
            $table->string('cancel_text');
            $table->string('necessary_cookie_title');
            $table->string('statistics_cookie_title');
            $table->string('marketing_cookie_title');
            $table->string('preferences_cookie_title');
            $table->text('necessary_cookie_description');
            $table->text('statistics_cookie_description');
            $table->text('marketing_cookie_description');
            $table->text('preferences_cookie_description');
            $table->boolean('show_reject_button')->default(true);
            $table->boolean('show_settings_button')->default(true);
            $table->boolean('show_categories_menu')->default(true);
            $table->boolean('show_statistics_category')->default(true);
            $table->boolean('show_marketing_category')->default(true);
            $table->boolean('show_preferences_category')->default(true);
            $table->string('manage_button_position')->default('right');
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
