<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('raw_data_files', function (Blueprint $table) {
            $table->string('source_type', 64)->nullable()->after('file_type');
        });

        $ids = DB::table('raw_data_files')->pluck('id');
        foreach ($ids as $id) {
            $sourceType = DB::table('raw_data_imports')
                ->where('raw_data_file_id', $id)
                ->whereNotNull('source_type')
                ->where('source_type', '!=', '')
                ->orderBy('id')
                ->value('source_type');
            if ($sourceType === null) {
                $sourceType = DB::table('raw_data_imports')
                    ->where('raw_data_file_id', $id)
                    ->orderBy('id')
                    ->value('source_type');
            }
            if ($sourceType !== null && trim((string) $sourceType) !== '') {
                DB::table('raw_data_files')->where('id', $id)->update([
                    'source_type' => trim((string) $sourceType),
                ]);
            }
        }
    }

    public function down(): void
    {
        Schema::table('raw_data_files', function (Blueprint $table) {
            $table->dropColumn('source_type');
        });
    }
};
