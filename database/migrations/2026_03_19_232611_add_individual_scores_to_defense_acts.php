<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('defense_acts', function (Blueprint $table) {
            $table->decimal('score_president', 5, 2)->nullable()->after('score');
            $table->decimal('score_secretary', 5, 2)->nullable()->after('score_president');
            $table->decimal('score_vocal', 5, 2)->nullable()->after('score_secretary');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('defense_acts', function (Blueprint $table) {
            //
        });
    }
};
