<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('model_kits', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('timeline_id')->constrained('timelines')->onDelete('cascade');
            $table->foreignId('grade_id')->constrained('grades')->onDelete('cascade');
            $table->foreignId('scale_id')->constrained('scales')->onDelete('cascade');
            $table->float('height_centimeters');
            $table->boolean('isPBandai');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('model_kits');
    }
};
