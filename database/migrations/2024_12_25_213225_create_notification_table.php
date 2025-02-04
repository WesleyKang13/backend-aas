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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->index();
            $table->string('sender')->index();
            $table->string('receiver')->index();
            $table->datetime('datetime')->nullable();
            $table->string('details');
            $table->string('attachment')->nullable();
            $table->enum('status', ['read', 'unread', 'draft']);
            $table->string('subject');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
