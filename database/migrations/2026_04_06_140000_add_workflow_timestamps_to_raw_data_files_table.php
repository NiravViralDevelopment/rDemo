<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('raw_data_files', function (Blueprint $table) {
            $table->timestamp('processed_at')->nullable()->after('file_type');
            $table->timestamp('rules_applied_at')->nullable()->after('processed_at');
        });
    }

    public function down(): void
    {
        Schema::table('raw_data_files', function (Blueprint $table) {
            $table->dropColumn(['processed_at', 'rules_applied_at']);
        });
    }
};
