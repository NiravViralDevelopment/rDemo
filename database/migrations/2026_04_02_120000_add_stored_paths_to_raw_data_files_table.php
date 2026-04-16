<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('raw_data_files', function (Blueprint $table) {
            $table->string('stored_upload', 500)->nullable()->after('filename');
            $table->string('stored_derived_csv', 500)->nullable()->after('stored_upload');
        });
    }

    public function down(): void
    {
        Schema::table('raw_data_files', function (Blueprint $table) {
            $table->dropColumn(['stored_upload', 'stored_derived_csv']);
        });
    }
};
