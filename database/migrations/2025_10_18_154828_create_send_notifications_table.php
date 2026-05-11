<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('send_notifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('to');
            $table->string('subject')->nullable();
            $table->longText('body');
            $table->json('cc')->nullable();
            $table->json('bcc')->nullable();
            $table->json('attachments')->nullable();
            $table->string('reply_to')->nullable();
            $table->string('channel')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('send_notifications');
    }
};
