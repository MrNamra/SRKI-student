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
        Schema::create('exam_infos', function (Blueprint $table) {
            $table->id();
            $table->string('en_no');
            $table->foreign('en_no')->references('enrollment_no')->on('students')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('exam_id')->references('id')->on('exams')->onDelete('cascade')->onUpdate('cascade');
            $table->longText('file_path')->nullable();
            $table->double('marks')->default(0);
            $table->string('ip')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_infos');
    }
};
