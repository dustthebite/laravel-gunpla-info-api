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
            $table->decimal('recommended_price_yen')->default(3000);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('model_kits', function (Blueprint $table) {
            $table->dropColumn('recommended_price_yen');
        });
    }
};
