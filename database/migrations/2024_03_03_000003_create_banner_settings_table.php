<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('banner_settings', function (Blueprint $table) {
            $table->id();
            $table->string('position')->default('bottom');
            $table->string('layout')->default('bar');
            $table->string('theme')->default('light');
            $table->string('button_style')->default('filled');
            $table->string('title')->default('Cookie Consent');
            $table->text('description')->default('We use cookies to enhance your browsing experience and analyze our traffic.');
            $table->string('accept_button_text')->default('Accept All');
            $table->string('reject_button_text')->default('Reject All');
            $table->string('settings_button_text')->default('Cookie Settings');
            $table->boolean('show_reject_button')->default(true);
            $table->boolean('show_settings_button')->default(true);
            $table->integer('z_index')->default(1000);
            $table->integer('padding')->default(20);
            $table->integer('margin')->default(20);
            $table->string('border_radius')->default('4px');
            $table->string('font_family')->default('inherit');
            $table->string('font_size')->default('14px');
            $table->string('primary_color')->default('#4CAF50');
            $table->string('secondary_color')->default('#2196F3');
            $table->string('background_color')->default('#ffffff');
            $table->string('text_color')->default('#000000');
            $table->json('cookie_categories')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('banner_settings');
    }
}; 