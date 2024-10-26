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
        Schema::create('assignment_info', function (Blueprint $table) {
            $table->id();
            $table->string('en_no');
            $table->foreign('en_no')->references('enrollment_no')->on('students')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('assingment_id')->references('id')->on('lab_schedules')->onDelete('cascade')->onUpdate('cascade');
            $table->longText('file_path');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assignment_info');
    }
};
