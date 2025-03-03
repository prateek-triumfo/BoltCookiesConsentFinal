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
            $table->string('position')->default('bottom'); // bottom, top, center
            $table->string('layout')->default('bar'); // bar, box, popup
            $table->string('theme')->default('light'); // light, dark
            $table->string('primary_color')->default('#007bff');
            $table->string('secondary_color')->default('#6c757d');
            $table->string('text_color')->default('#212529');
            $table->string('background_color')->default('#ffffff');
            $table->string('button_style')->default('filled'); // filled, outlined
            $table->string('title')->default('Cookie Consent');
            $table->text('description')->default('We use cookies to enhance your experience. By continuing to visit this site you agree to our use of cookies.');
            $table->string('accept_button_text')->default('Accept All');
            $table->string('reject_button_text')->default('Reject All');
            $table->string('settings_button_text')->default('Cookie Settings');
            $table->boolean('show_reject_button')->default(true);
            $table->boolean('show_settings_button')->default(true);
            $table->integer('z_index')->default(1000);
            $table->integer('padding')->default(15);
            $table->integer('margin')->default(15);
            $table->string('border_radius')->default('4px');
            $table->string('font_family')->default('inherit');
            $table->string('font_size')->default('14px');
            $table->boolean('is_active')->default(true);
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
