<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('raw_data_imports', 'receiver_gstin')) {
            Schema::table('raw_data_imports', function (Blueprint $table) {
                $table->text('receiver_gstin')->nullable()->after('customer_ship_to_gstid');
            });
        }

        if (! Schema::hasColumn('working_data', 'receiver_gstin')) {
            Schema::table('working_data', function (Blueprint $table) {
                $table->string('receiver_gstin', 20)->nullable()->after('customer_ship_to_gstid');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('raw_data_imports', 'receiver_gstin')) {
            Schema::table('raw_data_imports', function (Blueprint $table) {
                $table->dropColumn('receiver_gstin');
            });
        }

        if (Schema::hasColumn('working_data', 'receiver_gstin')) {
            Schema::table('working_data', function (Blueprint $table) {
                $table->dropColumn('receiver_gstin');
            });
        }
    }
};
