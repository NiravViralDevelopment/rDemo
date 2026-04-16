<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('raw_data_imports', function (Blueprint $table) {
            $table->id();

            $table->string('source_type')->nullable();
            $table->string('file_type')->nullable();

            // Seller / Invoice / Shipment
            $table->text('seller_gstin')->nullable();
            $table->text('invoice_number')->nullable();
            $table->text('invoice_date')->nullable();
            $table->text('transaction_type')->nullable();
            $table->text('order_id')->nullable();
            $table->text('shipment_id')->nullable();
            $table->text('shipment_date')->nullable();
            $table->text('order_date')->nullable();
            $table->text('shipment_item_id')->nullable();
            $table->text('quantity')->nullable();
            $table->text('item_description')->nullable();
            $table->text('asin')->nullable();
            $table->text('hsn_sac')->nullable();
            $table->text('sku')->nullable();
            $table->text('product_name')->nullable();
            $table->text('product_tax_code')->nullable();

            // Bill From / Ship From / Ship To
            $table->text('bill_from_city')->nullable();
            $table->text('bill_from_state')->nullable();
            $table->text('bill_from_country')->nullable();
            $table->text('bill_from_postal_code')->nullable();

            $table->text('ship_from_city')->nullable();
            $table->text('ship_from_state')->nullable();
            $table->text('ship_from_country')->nullable();
            $table->text('ship_from_postal_code')->nullable();

            $table->text('ship_to_city')->nullable();
            $table->text('ship_to_state')->nullable();
            $table->text('ship_to_country')->nullable();
            $table->text('ship_to_postal_code')->nullable();

            // Invoice totals
            $table->text('invoice_amount')->nullable();
            $table->text('tax_exclusive_gross')->nullable();
            $table->text('total_tax_amount')->nullable();

            // Tax rates (Cgst/Sgst/Utgst/Igst/Compensatory Cess)
            $table->text('cgst_rate')->nullable();
            $table->text('sgst_rate')->nullable();
            $table->text('utgst_rate')->nullable();
            $table->text('igst_rate')->nullable();
            $table->text('compensatory_cess_rate')->nullable();

            // Principal
            $table->text('principal_amount')->nullable();
            $table->text('principal_amount_basis')->nullable();

            // Tax amounts
            $table->text('cgst_tax')->nullable();
            $table->text('sgst_tax')->nullable();
            $table->text('utgst_tax')->nullable();
            $table->text('igst_tax')->nullable();
            $table->text('compensatory_cess_tax')->nullable();

            // Shipping taxes
            $table->text('shipping_amount')->nullable();
            $table->text('shipping_amount_basis')->nullable();
            $table->text('shipping_cgst_tax')->nullable();
            $table->text('shipping_sgst_tax')->nullable();
            $table->text('shipping_utgst_tax')->nullable();
            $table->text('shipping_igst_tax')->nullable();
            $table->text('shipping_cess_tax')->nullable();

            // Gift wrap taxes
            $table->text('gift_wrap_amount')->nullable();
            $table->text('gift_wrap_amount_basis')->nullable();
            $table->text('gift_wrap_cgst_tax')->nullable();
            $table->text('gift_wrap_sgst_tax')->nullable();
            $table->text('gift_wrap_utgst_tax')->nullable();
            $table->text('gift_wrap_igst_tax')->nullable();
            $table->text('gift_wrap_compensatory_cess_tax')->nullable();

            // Item promo / shipping promo / gift wrap promo
            $table->text('item_promo_discount')->nullable();
            $table->text('item_promo_discount_basis')->nullable();
            $table->text('item_promo_tax')->nullable();

            $table->text('shipping_promo_discount')->nullable();
            $table->text('shipping_promo_discount_basis')->nullable();
            $table->text('shipping_promo_tax')->nullable();

            $table->text('gift_wrap_promo_discount')->nullable();
            $table->text('gift_wrap_promo_discount_basis')->nullable();
            $table->text('gift_wrap_promo_tax')->nullable();

            // TCS
            $table->text('tcs_cgst_rate')->nullable();
            $table->text('tcs_cgst_amount')->nullable();
            $table->text('tcs_sgst_rate')->nullable();
            $table->text('tcs_sgst_amount')->nullable();
            $table->text('tcs_utgst_rate')->nullable();
            $table->text('tcs_utgst_amount')->nullable();
            $table->text('tcs_igst_rate')->nullable();
            $table->text('tcs_igst_amount')->nullable();

            // Warehouse / fulfilment / payment
            $table->text('warehouse_id')->nullable();
            $table->text('fulfillment_channel')->nullable();
            $table->text('payment_method_code')->nullable();

            // Bill To / GSTINs
            $table->text('bill_to_city')->nullable();
            $table->text('bill_to_state')->nullable();
            $table->text('bill_to_country')->nullable();
            $table->text('bill_to_postalcode')->nullable();
            $table->text('customer_bill_to_gstid')->nullable();
            $table->text('customer_ship_to_gstid')->nullable();

            // Customer info / IRN
            $table->text('buyer_name')->nullable();
            $table->text('credit_note_no')->nullable();
            $table->text('credit_note_date')->nullable();
            $table->text('irn_number')->nullable();
            $table->text('irn_filing_status')->nullable();
            $table->text('irn_date')->nullable();
            $table->text('irn_error_code')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('raw_data_imports');
    }
};

