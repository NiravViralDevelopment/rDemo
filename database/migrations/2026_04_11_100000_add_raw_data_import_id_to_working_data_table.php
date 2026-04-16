<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('working_data', function (Blueprint $table) {
            $table->unsignedBigInteger('raw_data_import_id')->nullable();
            $table->index('raw_data_import_id');
        });

        $driver = Schema::getConnection()->getDriverName();
        if ($driver === 'pgsql') {
            // Speeds B2C / B2B apply-rules filters on large files (PostgreSQL).
            DB::statement("
                CREATE INDEX IF NOT EXISTS working_data_raw_file_b2c_partial
                ON working_data (raw_data_file_id)
                WHERE lower(btrim(coalesce(b2b_b2c, ''))) = 'b2c'
            ");
        }
    }

    public function down(): void
    {
        $driver = Schema::getConnection()->getDriverName();
        if ($driver === 'pgsql') {
            DB::statement('DROP INDEX IF EXISTS working_data_raw_file_b2c_partial');
        }

        Schema::table('working_data', function (Blueprint $table) {
            $table->dropIndex(['raw_data_import_id']);
            $table->dropColumn('raw_data_import_id');
        });
    }
};
