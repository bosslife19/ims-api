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
        Schema::create('trackings', function (Blueprint $table) {
            $table->id();
            $table->foreignId("item_id")->constrained("items")->onDelete("cascade");
            $table->foreignId("school_id")->constrained("schools")->onDelete("cascade");
            $table->string("priority");
            $table->string("action");
            $table->integer("quantity");
            $table->string("reference_number");
            $table->string("additional_info")->nullable();
            $table->string("start_point")->default("warehouse");
            $table->string("current_point")->nullable();
            $table->timestamp("date_moved");
            $table->string("status")->default("pending");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trackings');
    }
};
