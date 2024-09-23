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
        Schema::create('new_items', function (Blueprint $table) {
            $table->string('item_code');
            $table->string('additional_info');
            $table->string('item_name');
            $table->string('subject_category');
            $table->string('distribution');
            $table->integer('quantity');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('new_items');
    }
};
