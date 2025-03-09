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
        Schema::table('model_kits', function (Blueprint $table) {
            $table->index('timeline_id');
            $table->index('grade_id');
            $table->index('height_centimeters');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('model_kits', function (Blueprint $table) {
            $table->dropIndex('timeline_id');
            $table->dropIndex('grade_id');
            $table->dropIndex('height_centimeters');
        });
    }
};
