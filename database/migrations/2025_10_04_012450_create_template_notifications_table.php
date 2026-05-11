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
        Schema::create('template_notifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('key');
            $table->string('locale');
            $table->string('subject');
            $table->longText('body_html');
            $table->integer('version');
            $table->boolean('is_active');
            $table->json('variables');
            $table->foreignUuid('created_by')->nullable()->constrained('users');
            $table->string('notification_channel', 16);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('template_notifications');
    }
};
