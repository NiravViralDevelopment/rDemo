<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('raw_data_files', function (Blueprint $table) {
            $table->string('file_type', 64)->nullable()->after('year');
        });
    }

    public function down(): void
    {
        Schema::table('raw_data_files', function (Blueprint $table) {
            $table->dropColumn('file_type');
        });
    }
};
