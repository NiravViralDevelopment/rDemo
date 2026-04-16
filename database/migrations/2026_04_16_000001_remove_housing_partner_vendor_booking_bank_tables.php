<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();

        Schema::dropIfExists('bank_transactions');
        Schema::dropIfExists('booking_payments');
        Schema::dropIfExists('vendor_materials');
        Schema::dropIfExists('vendor_transactions');
        Schema::dropIfExists('partner_payments');
        Schema::dropIfExists('bookings');
        Schema::dropIfExists('banks');
        Schema::dropIfExists('vendors');
        Schema::dropIfExists('partners');
        Schema::dropIfExists('properties');

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
