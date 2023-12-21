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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('type');
            $table->text('file');
            $table->unsignedBigInteger('semester_id');  // Add this line
            $table->unsignedBigInteger('department_id'); // Add this line
            $table->unsignedBigInteger('year_id'); // Add this line
            $table->foreign('semester_id')->references('id')->on('semesters');
            $table->foreign('department_id')->references('id')->on('departments');
            $table->foreign('year_id')->references('id')->on('years');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
