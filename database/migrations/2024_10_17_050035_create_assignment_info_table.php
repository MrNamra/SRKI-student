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
            $table->foreignId('en_no')->refrances('enrollment_no')->on('students');
            $table->foreignId('assingment_id')->refrances('id')->on('feculty_assignments');
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
