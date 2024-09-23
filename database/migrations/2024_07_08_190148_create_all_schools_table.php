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
        Schema::create('all_schools', function (Blueprint $table) {
            $table->id();
            $table->string('UPDATED_SCHOOL_ID');
            $table->string('SCHOOL_NAME');
            $table->string('LGA');
            $table->string('SENATORIAL_DISTRICT');
            $table->string('SCHOOL_TYPE');
            $table->string('PUPIL_COUNT');
            $table->string('TEACHER_COUNT');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('all_schools');
    }
};
