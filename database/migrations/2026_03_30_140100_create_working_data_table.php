<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('working_data', function (Blueprint $table) {
            $table->id();

            $table->string('b2b_b2c', 20)->nullable();
            $table->string('seller_gstin', 20)->nullable();
            $table->string('ship_from_state', 100)->nullable();
            $table->string('invoice_number', 100)->nullable();
            $table->date('invoice_date')->nullable();
            $table->string('customer_bill_to_gstid', 20)->nullable();
            $table->string('buyer_name', 255)->nullable();
            $table->string('bill_to_city', 100)->nullable();
            $table->string('bill_to_state', 100)->nullable();
            $table->string('transaction_type', 100)->nullable();
            $table->string('for_sap', 100)->nullable();
            $table->string('order_id', 100)->nullable();
            $table->date('shipment_date')->nullable();
            $table->date('order_date')->nullable();
            $table->unsignedInteger('quantity')->nullable();
            $table->string('item_description', 500)->nullable();
            $table->string('hsn_sac', 50)->nullable();
            $table->string('sku', 100)->nullable();
            $table->string('product_name', 255)->nullable();
            $table->string('bill_from_city', 255)->nullable();
            $table->string('bill_from_state', 255)->nullable();
            $table->string('bill_from_country', 255)->nullable();
            $table->string('bill_from_postal_code', 100)->nullable();

            $table->string('ship_from_city', 255)->nullable();
            $table->string('ship_from_country', 255)->nullable();
            $table->string('ship_from_postal_code', 100)->nullable();

            $table->string('ship_to_city', 255)->nullable();
            $table->string('ship_to_state', 255)->nullable();
            $table->string('ship_to_country', 255)->nullable();
            $table->string('ship_to_postal_code', 100)->nullable();

            
            $table->decimal('invoice_amount', 15, 2)->nullable();
            $table->decimal('tax_exclusive_gross_taxable_value', 15, 2)->nullable();
            $table->decimal('total_tax_amount', 15, 2)->nullable();
            $table->decimal('gst_rate', 8, 2)->nullable();
            $table->decimal('cgst', 15, 2)->nullable();
            $table->decimal('sgst', 15, 2)->nullable();
            $table->decimal('igst', 15, 2)->nullable();
            $table->decimal('total_gst', 15, 2)->nullable();
            $table->decimal('compensatory_cess_tax', 15, 2)->nullable();
            $table->decimal('shipping_amount', 15, 2)->nullable();
            $table->string('shipping_amount_basis', 100)->nullable();
            $table->decimal('shipping_cgst_tax', 15, 2)->nullable();
            $table->decimal('shipping_sgst_tax', 15, 2)->nullable();
            $table->decimal('shipping_utgst_tax', 15, 2)->nullable();
            $table->decimal('shipping_igst_tax', 15, 2)->nullable();
            $table->decimal('shipping_cess_tax', 15, 2)->nullable();
            $table->decimal('gift_wrap_amount', 15, 2)->nullable();
            $table->string('gift_wrap_amount_basis', 100)->nullable();
            $table->decimal('gift_wrap_cgst_tax', 15, 2)->nullable();
            $table->decimal('gift_wrap_sgst_tax', 15, 2)->nullable();
            $table->decimal('gift_wrap_utgst_tax', 15, 2)->nullable();
            $table->decimal('gift_wrap_igst_tax', 15, 2)->nullable();
            $table->decimal('gift_wrap_compensatory_cess_tax', 15, 2)->nullable();
            $table->decimal('item_promo_discount', 15, 2)->nullable();
            $table->string('item_promo_discount_basis', 100)->nullable();
            $table->decimal('item_promo_tax', 15, 2)->nullable();
            $table->decimal('shipping_promo_discount', 15, 2)->nullable();
            $table->string('shipping_promo_discount_basis', 100)->nullable();
            $table->decimal('shipping_promo_tax', 15, 2)->nullable();
            $table->decimal('gift_wrap_promo_discount', 15, 2)->nullable();
            $table->string('gift_wrap_promo_discount_basis', 100)->nullable();
            $table->decimal('gift_wrap_promo_tax', 15, 2)->nullable();
            $table->decimal('tcs_cgst_rate', 8, 2)->nullable();
            $table->decimal('tcs_cgst_amount', 15, 2)->nullable();
            $table->decimal('tcs_sgst_rate', 8, 2)->nullable();
            $table->decimal('tcs_sgst_amount', 15, 2)->nullable();
            $table->decimal('tcs_utgst_rate', 8, 2)->nullable();
            $table->decimal('tcs_utgst_amount', 15, 2)->nullable();
            $table->decimal('tcs_igst_rate', 8, 2)->nullable();
            $table->decimal('tcs_igst_amount', 15, 2)->nullable();
            $table->string('warehouse_id')->nullable();
            $table->string('fulfillment_channel', 100)->nullable();
            $table->string('payment_method_code', 100)->nullable();
            $table->string('bill_to_country', 100)->nullable();
            $table->string('bill_to_postalcode', 20)->nullable();
            $table->string('customer_ship_to_gstid', 20)->nullable();
            $table->string('credit_note_no', 100)->nullable();
            $table->date('credit_note_date')->nullable();
            $table->string('irn_number', 150)->nullable();
            $table->string('irn_filing_status', 100)->nullable();
            $table->date('irn_date')->nullable();
            $table->string('irn_error_code', 100)->nullable();
            $table->unsignedBigInteger('raw_data_file_id')->nullable();


            
            
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('working_data');
    }
};

