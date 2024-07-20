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
        Schema::create('student_clocks', function (Blueprint $table) {
            $table->id();
            $table->integer('student_id')->index();
            $table->integer('class_id')->index();
            $table->enum('type', ['in','out']);
            $table->string('ip_address');
            $table->timestamp('timestamp')->nullable();
            $table->boolean('enabled')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_clocks');
    }
};
