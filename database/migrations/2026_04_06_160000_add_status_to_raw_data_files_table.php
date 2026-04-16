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
            $table->string('status', 32)->default('imported')->after('rules_applied_at');
        });

        DB::table('raw_data_files')->whereNotNull('rules_applied_at')->update(['status' => 'rule_applied']);

        DB::table('raw_data_files')
            ->whereNull('rules_applied_at')
            ->where(function ($q) {
                $q->whereNotNull('processed_at')
                    ->orWhereIn('id', function ($sub) {
                        $sub->select('raw_data_file_id')
                            ->from('working_data')
                            ->whereNotNull('raw_data_file_id')
                            ->distinct();
                    });
            })
            ->update(['status' => 'processed']);
    }

    public function down(): void
    {
        Schema::table('raw_data_files', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
