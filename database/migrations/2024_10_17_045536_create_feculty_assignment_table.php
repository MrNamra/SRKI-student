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
        Schema::create('feculty_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lab_id')->constrained('lab_schedules')->on('lab_schedules')->onDelete('cascade')->onUpdate('cascade');
            $table->text('path');
            $table->string('title');
            $table->integer('assignment_no');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feculty_assignments');
    }
};
