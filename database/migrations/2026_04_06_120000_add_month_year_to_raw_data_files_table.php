<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('raw_data_files', function (Blueprint $table) {
            $table->unsignedTinyInteger('month')->nullable()->after('stored_derived_csv');
            $table->unsignedSmallInteger('year')->nullable()->after('month');
        });
    }

    public function down(): void
    {
        Schema::table('raw_data_files', function (Blueprint $table) {
            $table->dropColumn(['month', 'year']);
        });
    }
};
