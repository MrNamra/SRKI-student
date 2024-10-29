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
        Schema::create('exams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sub_id')->references('id')->on('subjects')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('course_id')->references('id')->on('courses')->onDelete('cascade')->onUpdate('cascade');
            $table->string('div');
            $table->string('title');
            $table->longText('dec')->nullable();
            $table->longText('file_path')->nullable();
            $table->string('examtype');
            $table->dateTime('StartTime');
            $table->dateTime('EndTime');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exams');
    }
};
