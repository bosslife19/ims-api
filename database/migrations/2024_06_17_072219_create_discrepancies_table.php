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
        Schema::create('discrepancies', function (Blueprint $table) {
            $table->id();
            $table->string("report_id")->unique();
            $table->string("reporter");
            $table->string("item_name");
            $table->string("supplier");
            $table->integer("expected_quantity");
            $table->integer("actual_quantity");
            $table->string("discrepancy_type");
            $table->string("description");
            $table->string("date");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discrepancies');
    }
};
