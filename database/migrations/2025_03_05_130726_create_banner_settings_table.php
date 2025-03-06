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
            $table->string('accept_button_text', 50);
            $table->string('reject_button_text', 50);
            $table->string('manage_button_text', 50);
            $table->string('save_button_text', 50);
            $table->string('cancel_button_text', 50);
            $table->string('banner_background_color', 7);
            $table->string('banner_text_color', 7);
            $table->string('button_background_color', 7);
            $table->string('button_text_color', 7);
            $table->string('font_family');
            $table->string('font_size');
            $table->boolean('show_reject_button')->default(true);
            $table->boolean('show_manage_button')->default(true);
            $table->boolean('show_statistics')->default(true);
            $table->boolean('show_marketing')->default(true);
            $table->boolean('show_preferences')->default(true);
            $table->enum('button_position', ['left', 'right'])->default('right');
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
