<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Csv as CsvWriter;

class RawDataImportController extends Controller
{
    private const IMPORT_COLUMNS = [
        'seller_gstin',
        'invoice_number',
        'invoice_date',
        'transaction_type',
        'order_id',
        'shipment_id',
        'shipment_date',
        'order_date',
        'shipment_item_id',
        'quantity',
        'item_description',
        'asin',
        'hsn_sac',
        'sku',
        'product_name',
        'product_tax_code',
        'bill_from_city',
        'bill_from_state',
        'bill_from_country',
        'bill_from_postal_code',
        'ship_from_city',
        'ship_from_state',
        'ship_from_country',
        'ship_from_postal_code',
        'ship_to_city',
        'ship_to_state',
        'ship_to_country',
        'ship_to_postal_code',
        'invoice_amount',
        'tax_exclusive_gross',
        'total_tax_amount',
        'cgst_rate',
        'sgst_rate',
        'utgst_rate',
        'igst_rate',
        'compensatory_cess_rate',
        'principal_amount',
        'principal_amount_basis',
        'cgst_tax',
        'sgst_tax',
        'utgst_tax',
        'igst_tax',
        'compensatory_cess_tax',
        'shipping_amount',
        'shipping_amount_basis',
        'shipping_cgst_tax',
        'shipping_sgst_tax',
        'shipping_utgst_tax',
        'shipping_igst_tax',
        'shipping_cess_tax',
        'gift_wrap_amount',
        'gift_wrap_amount_basis',
        'gift_wrap_cgst_tax',
        'gift_wrap_sgst_tax',
        'gift_wrap_utgst_tax',
        'gift_wrap_igst_tax',
        'gift_wrap_compensatory_cess_tax',
        'item_promo_discount',
        'item_promo_discount_basis',
        'item_promo_tax',
        'shipping_promo_discount',
        'shipping_promo_discount_basis',
        'shipping_promo_tax',
        'gift_wrap_promo_discount',
        'gift_wrap_promo_discount_basis',
        'gift_wrap_promo_tax',
        'tcs_cgst_rate',
        'tcs_cgst_amount',
        'tcs_sgst_rate',
        'tcs_sgst_amount',
        'tcs_utgst_rate',
        'tcs_utgst_amount',
        'tcs_igst_rate',
        'tcs_igst_amount',
        'warehouse_id',
        'fulfillment_channel',
        'payment_method_code',
        'bill_to_city',
        'bill_to_state',
        'bill_to_country',
        'bill_to_postalcode',
        'customer_bill_to_gstid',
        'customer_ship_to_gstid',
        'receiver_gstin',
        'buyer_name',
        'credit_note_no',
        'credit_note_date',
        'irn_number',
        'irn_filing_status',
        'irn_date',
        'irn_error_code',
    ];

    /** File type value from import form; stock-transfer sheets use the same headers as B2B but a different column order. */
    private const FILE_TYPE_STOCK_TRANSFER = 'STOCK_TRANSFER';

    /**
     * Minimum number of header cells that resolve to IMPORT_COLUMNS before we map by header name
     * (column order in the sheet may differ from the database insert order).
     */
    private const MIN_IMPORT_HEADER_MATCHES = 6;

    /**
     * Map normalized header labels (after {@see self::normalizeImportHeaderLabel}) to IMPORT_COLUMNS keys when the raw header would not match.
     *
     * @var array<string, string>
     */
    private const IMPORT_HEADER_ALIASES = [
        'bill_to_postal_code' => 'bill_to_postalcode',
        'bill_to_postal_code_' => 'bill_to_postalcode',
        'customer_bill_to_gstin' => 'customer_bill_to_gstid',
        'customer_ship_to_gstin' => 'customer_ship_to_gstid',
        'gstin_of_receiver' => 'receiver_gstin',
        // Amazon / Excel variants
        'tax_exclusive_gross_taxable_value' => 'tax_exclusive_gross',
        'tax_exclusive_gross_value' => 'tax_exclusive_gross',
        'hsn_sac_code' => 'hsn_sac',
        'hsn_sac_' => 'hsn_sac',
        'ship_to_postalcode' => 'ship_to_postal_code',
        'ship_to_postal_code_' => 'ship_to_postal_code',
        'bill_from_postalcode' => 'bill_from_postal_code',
        'ship_from_postalcode' => 'ship_from_postal_code',
        'shipment_item_code' => 'shipment_item_id',
    ];

    public function index()
    {
        // Must match the default month/year on the import form (import.blade.php uses previous calendar month).
        $defaultImportPeriod = Carbon::now()->startOfMonth()->subMonth();
        $hasImportForDefaultPeriod = DB::table('raw_data_files')
            ->where('month', $defaultImportPeriod->month)
            ->where('year', $defaultImportPeriod->year)
            ->where('source_type', 'Amazon')
            ->exists();

        $groups = DB::table('raw_data_files')
            ->whereNotNull('month')
            ->whereNotNull('year')
            ->selectRaw('month, year, COALESCE(source_type, \'\') as source_type, MIN(created_at) as uploaded_at, MAX(updated_at) as updated_at, COUNT(*) as files_count')
            ->groupByRaw('month, year, COALESCE(source_type, \'\')')
            ->orderByDesc('year')
            ->orderByDesc('month')
            ->orderBy('source_type')
            ->get();

        foreach ($groups as $g) {
            $statuses = DB::table('raw_data_files')
                ->where('month', $g->month)
                ->where('year', $g->year)
                ->whereRaw('COALESCE(source_type, \'\') = ?', [$g->source_type])
                ->pluck('status')
                ->map(fn ($s) => $this->normalizeWorkflowStatus($s))
                ->values()
                ->all();
            $g->display_status = $this->aggregatePeriodStatus($statuses);
        }

        return view('raw_data_import.index', compact('groups', 'hasImportForDefaultPeriod'));
    }

    /**
     * Detail view for one calendar period (all raw_data_files rows for that month/year/source).
     */
    public function showPeriod(Request $request)
    {
        $request->validate([
            'month' => 'required|integer|between:1,12',
            'year' => 'required|integer|min:2000|max:2100',
            'source_type' => 'nullable|string|max:64',
        ]);

        $month = (int) $request->input('month');
        $year = (int) $request->input('year');
        $sourceType = $request->input('source_type');

        $q = DB::table('raw_data_files')
            ->where('month', $month)
            ->where('year', $year);
        if ($sourceType === null || $sourceType === '') {
            $q->where(function ($q2) {
                $q2->whereNull('source_type')->orWhere('source_type', '');
            });
        } else {
            $q->where('source_type', $sourceType);
        }

        $files = $q->orderBy('id')->get();
        if ($files->isEmpty()) {
            return redirect()->route('raw-data-import')->with('error', 'No data found for this period.');
        }

        $periodLabel = Carbon::createFromDate($year, $month, 1)->format('F Y');

        $statuses = $files->pluck('status')->map(fn ($s) => $this->normalizeWorkflowStatus($s))->values()->all();
        $displayStatus = $this->aggregatePeriodStatus($statuses);

        $uploadedAt = $files->min('created_at');
        $updatedAt = $files->max('updated_at');

        $fileByType = [];
        foreach ($files as $f) {
            $key = $this->normalizeFileTypeForDuplicateKey($f->file_type ?? '');
            if ($key !== '') {
                $fileByType[$key] = $f;
            }
        }

        $allRequiredAmazonFilesPresent = isset($fileByType['b2b'], $fileByType['b2c'], $fileByType['STOCK_TRANSFER']);
        $allRequiredAmazonFilesProcessed = false;
        if ($allRequiredAmazonFilesPresent) {
            $allRequiredAmazonFilesProcessed = true;
            foreach (['b2b', 'b2c', 'STOCK_TRANSFER'] as $requiredKey) {
                $s = $this->normalizeWorkflowStatus($fileByType[$requiredKey]->status ?? null);
                if (! in_array($s, ['processed', 'rule_applied'], true)) {
                    $allRequiredAmazonFilesProcessed = false;
                    break;
                }
            }
        }
        $showPeriodProcessButton = ! $allRequiredAmazonFilesProcessed;

        return view('raw_data_import.period_show', compact(
            'month',
            'year',
            'sourceType',
            'periodLabel',
            'displayStatus',
            'uploadedAt',
            'updatedAt',
            'fileByType',
            'showPeriodProcessButton'
        ));
    }

    /**
     * Delete all raw_data_files (and related data) for a calendar period + source.
     */
    public function destroyPeriod(Request $request)
    {
        $request->validate([
            'month' => 'required|integer|between:1,12',
            'year' => 'required|integer|min:2000|max:2100',
            'source_type' => 'nullable|string|max:64',
        ]);

        $month = (int) $request->input('month');
        $year = (int) $request->input('year');
        $sourceType = $request->input('source_type');

        $q = DB::table('raw_data_files')
            ->where('month', $month)
            ->where('year', $year);
        if ($sourceType === null || $sourceType === '') {
            $q->where(function ($q2) {
                $q2->whereNull('source_type')->orWhere('source_type', '');
            });
        } else {
            $q->where('source_type', $sourceType);
        }

        $files = $q->orderBy('id')->get();
        if ($files->isEmpty()) {
            return redirect()->route('raw-data-import')->with('error', 'No records found for this period.');
        }

        foreach ($files as $file) {
            $this->deleteRawDataFileRecord((int) $file->id);
        }

        return redirect()->route('raw-data-import')->with('message', 'All imports for this period were removed.');
    }

    /**
     * Normalize raw_data_files.status for workflow logic (case-insensitive).
     */
    private function normalizeWorkflowStatus(?string $status): string
    {
        $s = strtolower(trim((string) $status));
        if ($s === '' || $s === 'imported') {
            return 'imported';
        }
        if ($s === 'processed') {
            return 'processed';
        }
        if ($s === 'rule_applied' || $s === 'ruleapplied') {
            return 'rule_applied';
        }

        return 'imported';
    }

    /**
     * Roll up child file statuses for one period row on the index.
     *
     * @param  array<int, string>  $statuses  normalized via {@see normalizeWorkflowStatus}
     */
    private function aggregatePeriodStatus(array $statuses): string
    {
        $statuses = array_map(fn ($s) => $this->normalizeWorkflowStatus($s), $statuses);

        if ($statuses === []) {
            return 'pending';
        }

        $count = count($statuses);
        $ruleAppliedCount = count(array_filter($statuses, fn ($s) => $s === 'rule_applied'));
        if ($ruleAppliedCount === $count) {
            return 'rule_applied';
        }
        if ($ruleAppliedCount > 0) {
            return 'in_progress';
        }

        $processedCount = count(array_filter($statuses, fn ($s) => $s === 'processed'));
        if ($processedCount === $count) {
            return 'processed';
        }
        if ($processedCount > 0) {
            return 'in_progress';
        }

        return 'pending';
    }

    public function showImportForm()
    {
        return view('raw_data_import.import');
    }

    /**
     * Live check: whether an import already exists for month/year + source + file type (same rules as POST import).
     */
    public function checkDuplicateImport(Request $request)
    {
        $request->validate([
            'month' => 'required|integer|between:1,12',
            'year' => 'required|integer|min:2000|max:2100',
            'source_type' => 'required|in:Amazon,Blinkit,Flipkart',
            'file_type' => 'required|in:b2b,b2c,STOCK_TRANSFER',
        ]);

        $exists = $this->importCombinationExists(
            (int) $request->input('month'),
            (int) $request->input('year'),
            (string) $request->input('file_type'),
            (string) $request->input('source_type')
        );

        return response()->json(['exists' => $exists]);
    }

    public function import(Request $request)
    {
        $request->validate([
            'source_type' => 'required|in:Amazon,Blinkit,Flipkart',
            'month' => 'required|integer|between:1,12',
            'year' => 'required|integer|min:2000|max:2100',
        ]);

        $month = (int) $request->input('month');
        $year = (int) $request->input('year');
        $sourceType = (string) $request->input('source_type', 'Amazon');

        if ($sourceType === 'Amazon') {
            $request->validate([
                'file_amazon_b2b' => 'nullable|file|mimes:csv,txt,xlsx',
                'file_amazon_b2c' => 'nullable|file|mimes:csv,txt,xlsx',
                'file_amazon_stock_transfer' => 'nullable|file|mimes:csv,txt,xlsx',
            ]);

            $amazonSlots = [
                ['input' => 'file_amazon_b2b', 'file_type' => 'b2b'],
                ['input' => 'file_amazon_b2c', 'file_type' => 'b2c'],
                ['input' => 'file_amazon_stock_transfer', 'file_type' => self::FILE_TYPE_STOCK_TRANSFER],
            ];

            $toImport = [];
            foreach ($amazonSlots as $slot) {
                if (!$request->hasFile($slot['input'])) {
                    continue;
                }
                $file = $request->file($slot['input']);
                if (!$file || !$file->isValid()) {
                    continue;
                }
                $toImport[] = ['file' => $file, 'file_type' => $slot['file_type']];
            }

            if ($toImport === []) {
                return back()
                    ->withErrors(['file_amazon' => 'Please upload at least one Amazon file (B2B, B2C, or STOCK_TRANSFER).'])
                    ->withInput();
            }

            $periodLabel = Carbon::createFromDate($year, $month, 1)->format('F Y');
            foreach ($toImport as $item) {
                if ($this->importCombinationExists($month, $year, $item['file_type'], $sourceType)) {
                    return back()
                        ->withErrors([
                            'duplicate' => 'Import already exists for '.$periodLabel.' for Amazon '
                                .$this->fileTypeLabelForMessage($item['file_type'])
                                .'. Remove that file or use a different period.',
                        ])
                        ->withInput();
                }
            }

            foreach ($toImport as $item) {
                $this->importSingleUploadedFile($item['file'], $month, $year, $sourceType, $item['file_type']);
            }

            $n = count($toImport);

            return redirect()->route('raw-data-import')->with(
                'message',
                $n === 1 ? 'Import Done' : 'Import Done ('.$n.' Amazon files).'
            );
        }

        $request->validate([
            'file' => 'required|file|mimes:csv,txt,xlsx',
            'file_type' => 'required|in:b2b,b2c,STOCK_TRANSFER',
        ]);

        $fileType = (string) $request->input('file_type');

        if ($this->importCombinationExists($month, $year, $fileType, $sourceType)) {
            $periodLabel = Carbon::createFromDate($year, $month, 1)->format('F Y');

            return back()
                ->withErrors([
                    'duplicate' => 'Import already exists for '.$periodLabel.' with this source type and file type. Choose a different source, file type, or wait until the period changes.',
                ])
                ->withInput();
        }

        $this->importSingleUploadedFile($request->file('file'), $month, $year, $sourceType, $fileType);

        return redirect()->route('raw-data-import')->with('message', 'Import Done');
    }

    /**
     * Store one uploaded file, create raw_data_files row (source_type + file_type), and load raw rows.
     */
    private function importSingleUploadedFile(
        UploadedFile $file,
        int $month,
        int $year,
        string $sourceType,
        string $fileType
    ): void {
        $originalName = $file->getClientOriginalName();

        $ext = strtolower($file->getClientOriginalExtension() ?: 'csv');
        $safeBase = preg_replace('/[^a-zA-Z0-9_-]/', '_', pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
        $base = time().'_'.uniqid('', true).'_'.$safeBase;

        $uploadedName = $base.'.'.$ext;
        $file->move(storage_path('app'), $uploadedName);
        $uploadedPath = storage_path('app/'.$uploadedName);

        $rawDataFileId = DB::table('raw_data_files')->insertGetId([
            'filename' => $originalName,
            'month' => $month,
            'year' => $year,
            'file_type' => $fileType,
            'source_type' => $sourceType,
            'status' => 'imported',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        if ($ext === 'xlsx') {
            $spreadsheet = IOFactory::load($uploadedPath);
            $spreadsheet->setActiveSheetIndex(0);

            $writer = new CsvWriter($spreadsheet);
            $writer->setDelimiter(',');
            $writer->setEnclosure('"');
            $writer->setLineEnding("\n");

            $csvName = $base.'.csv';
            $csvPath = storage_path('app/'.$csvName);
            $writer->save($csvPath);

            $path = str_replace('\\', '/', $csvPath);
        } else {
            $path = storage_path('app/'.$uploadedName);
            $path = str_replace('\\', '/', $path);
        }

        $this->importRawDataRows($path, $rawDataFileId, $sourceType, $fileType);
    }

    private function fileTypeLabelForMessage(string $fileType): string
    {
        $ft = $this->normalizeFileTypeForDuplicateKey($fileType);

        return match ($ft) {
            'b2b' => 'B2B',
            'b2c' => 'B2C',
            'STOCK_TRANSFER' => 'STOCK_TRANSFER',
            default => $fileType,
        };
    }

    /**
     * Copy raw_data_imports into working_data for one file.
     *
     * @return array{ok: bool, inserted?: int, error?: string}
     */
    private function processFileToWorkingData(int $id): array
    {
        $driver = DB::connection()->getDriverName();
        $file = DB::table('raw_data_files')->where('id', $id)->first();
        if (!$file) {
            return ['ok' => false, 'error' => 'File record not found.'];
        }

        $linkedCount = DB::table('raw_data_imports')->where('raw_data_file_id', $id)->count();

        // Backward-compatibility for old imports done before raw_data_file_id mapping.
        // IMPORTANT: Do NOT assign ALL NULL imports to the current file id, otherwise B2B/B2C
        // processing can mix together. We only re-link rows created around this upload time.
        if ($linkedCount === 0) {
            $unlinkedQuery = DB::table('raw_data_imports')->whereNull('raw_data_file_id');

            // raw_data_files.created_at is set right before importing rows in import().
            // So matching a small time window prevents re-linking unrelated legacy imports.
            $from = isset($file->created_at) ? $file->created_at : now();
            $to = (clone $from)->addMinutes(5);

            $unlinkedCount = (clone $unlinkedQuery)->whereBetween('created_at', [$from, $to])->count();
            if ($unlinkedCount > 0) {
                DB::table('raw_data_imports')
                    ->whereNull('raw_data_file_id')
                    ->whereBetween('created_at', [$from, $to])
                    ->update([
                        'raw_data_file_id' => $id,
                        'updated_at' => now(),
                    ]);

                $linkedCount = DB::table('raw_data_imports')->where('raw_data_file_id', $id)->count();
            }
        }

        if ($linkedCount === 0) {
            return [
                'ok' => false,
                'error' => 'No raw import rows found for this file. Please import the file again, then click Processing.',
            ];
        }

        // Re-process safely for this file.
        DB::table('working_data')->where('raw_data_file_id', $id)->delete();

        $invoiceDateSelect = 'r.invoice_date';
        $shipmentDateSelect = 'r.shipment_date';
        $orderDateSelect = 'r.order_date';
        $creditNoteDateSelect = 'r.credit_note_date';
        $irnDateSelect = 'r.irn_date';
        $quantitySelect = 'r.quantity';
        $invoiceAmountSelect = 'r.invoice_amount';
        $taxExclusiveGrossSelect = 'r.tax_exclusive_gross';
        $totalTaxAmountSelect = 'r.total_tax_amount';
        $gstRateSelect = 'r.igst_rate';
        $cgstSelect = 'r.cgst_tax';
        $sgstSelect = 'r.sgst_tax';
        $igstSelect = 'r.igst_tax';
        $totalGstSelect = 'r.total_tax_amount';
        $compensatoryCessTaxSelect = 'r.compensatory_cess_tax';
        $shippingAmountSelect = 'r.shipping_amount';
        $shippingAmountBasisSelect = 'r.shipping_amount_basis';
        $shippingCgstTaxSelect = 'r.shipping_cgst_tax';
        $shippingSgstTaxSelect = 'r.shipping_sgst_tax';
        $shippingUtgstTaxSelect = 'r.shipping_utgst_tax';
        $shippingIgstTaxSelect = 'r.shipping_igst_tax';
        $shippingCessTaxSelect = 'r.shipping_cess_tax';
        $giftWrapAmountSelect = 'r.gift_wrap_amount';
        $giftWrapAmountBasisSelect = 'r.gift_wrap_amount_basis';
        $giftWrapCgstTaxSelect = 'r.gift_wrap_cgst_tax';
        $giftWrapSgstTaxSelect = 'r.gift_wrap_sgst_tax';
        $giftWrapUtgstTaxSelect = 'r.gift_wrap_utgst_tax';
        $giftWrapIgstTaxSelect = 'r.gift_wrap_igst_tax';
        $giftWrapCompensatoryCessTaxSelect = 'r.gift_wrap_compensatory_cess_tax';
        $itemPromoDiscountSelect = 'r.item_promo_discount';
        $itemPromoDiscountBasisSelect = 'r.item_promo_discount_basis';
        $itemPromoTaxSelect = 'r.item_promo_tax';
        $shippingPromoDiscountSelect = 'r.shipping_promo_discount';
        $shippingPromoDiscountBasisSelect = 'r.shipping_promo_discount_basis';
        $shippingPromoTaxSelect = 'r.shipping_promo_tax';
        $giftWrapPromoDiscountSelect = 'r.gift_wrap_promo_discount';
        $giftWrapPromoDiscountBasisSelect = 'r.gift_wrap_promo_discount_basis';
        $giftWrapPromoTaxSelect = 'r.gift_wrap_promo_tax';
        $tcsCgstRateSelect = 'r.tcs_cgst_rate';
        $tcsCgstAmountSelect = 'r.tcs_cgst_amount';
        $tcsSgstRateSelect = 'r.tcs_sgst_rate';
        $tcsSgstAmountSelect = 'r.tcs_sgst_amount';
        $tcsUtgstRateSelect = 'r.tcs_utgst_rate';
        $tcsUtgstAmountSelect = 'r.tcs_utgst_amount';
        $tcsIgstRateSelect = 'r.tcs_igst_rate';
        $tcsIgstAmountSelect = 'r.tcs_igst_amount';
        // warehouse_id must stay as text (Amazon can send non-integer IDs). Do not cast to bigint on PostgreSQL.
        $warehouseIdSelect = 'r.warehouse_id';
        if (in_array($driver, ['mysql', 'mariadb'], true)) {
            $warehouseIdSelect = "NULLIF(TRIM(r.warehouse_id), '')";
        }
        if ($driver === 'pgsql') {
            $toPgDate = static function (string $column): string {
                return "
                    CASE
                        WHEN trim(both from coalesce(r.{$column}, '')) = '' THEN NULL
                        WHEN trim(both from r.{$column}) ~ '^[0-9]{4}-[0-9]{2}-[0-9]{2}$'
                            THEN to_date(trim(both from r.{$column}), 'YYYY-MM-DD')
                        WHEN trim(both from r.{$column}) ~ '^[0-9]{2}-[0-9]{2}-[0-9]{4}$'
                            THEN to_date(trim(both from r.{$column}), 'DD-MM-YYYY')
                        WHEN trim(both from r.{$column}) ~ '^[0-9]{1,2}-[0-9]{1,2}-[0-9]{4}( [0-9]{1,2}:[0-9]{2}(:[0-9]{2})?)?$'
                            THEN to_date(split_part(trim(both from r.{$column}), ' ', 1), 'FMMM-FMDD-YYYY')
                        WHEN trim(both from r.{$column}) ~ '^[0-9]{2}/[0-9]{2}/[0-9]{4}$'
                            THEN to_date(trim(both from r.{$column}), 'DD/MM/YYYY')
                        WHEN trim(both from r.{$column}) ~ '^[0-9]{1,2}/[0-9]{1,2}/[0-9]{4}( [0-9]{1,2}:[0-9]{2}(:[0-9]{2})?)?$'
                            THEN to_date(split_part(trim(both from r.{$column}), ' ', 1), 'FMMM/FMDD/YYYY')
                        ELSE NULL
                    END
                ";
            };
            $toPgNumeric = static function (string $column): string {
                return "
                    CASE
                        WHEN replace(trim(both from coalesce(r.{$column}, '')), ',', '') ~ '^-?([0-9]+(\\.[0-9]+)?|\\.[0-9]+)$'
                            THEN replace(trim(both from r.{$column}), ',', '')::numeric
                        ELSE NULL
                    END
                ";
            };
            $toPgInteger = static function (string $column): string {
                return "
                    CASE
                        WHEN replace(trim(both from coalesce(r.{$column}, '')), ',', '') ~ '^-?[0-9]+$'
                            THEN replace(trim(both from r.{$column}), ',', '')::integer
                        ELSE NULL
                    END
                ";
            };
            $toPgBigInt = static function (string $column): string {
                return "
                    CASE
                        WHEN replace(trim(both from coalesce(r.{$column}, '')), ',', '') ~ '^-?[0-9]+$'
                            THEN replace(trim(both from r.{$column}), ',', '')::bigint
                        ELSE NULL
                    END
                ";
            };

            // Parse incoming text date safely for DATE-typed columns.
            $invoiceDateSelect = $toPgDate('invoice_date');
            $shipmentDateSelect = $toPgDate('shipment_date');
            $orderDateSelect = $toPgDate('order_date');
            $creditNoteDateSelect = $toPgDate('credit_note_date');
            $irnDateSelect = $toPgDate('irn_date');
            $quantitySelect = $toPgInteger('quantity');
            $invoiceAmountSelect = $toPgNumeric('invoice_amount');
            $taxExclusiveGrossSelect = $toPgNumeric('tax_exclusive_gross');
            $totalTaxAmountSelect = $toPgNumeric('total_tax_amount');
            $gstRateSelect = $toPgNumeric('igst_rate');
            $cgstSelect = $toPgNumeric('cgst_tax');
            $sgstSelect = $toPgNumeric('sgst_tax');
            $igstSelect = $toPgNumeric('igst_tax');
            $totalGstSelect = $toPgNumeric('total_tax_amount');
            $compensatoryCessTaxSelect = $toPgNumeric('compensatory_cess_tax');
            $shippingAmountSelect = $toPgNumeric('shipping_amount');
            $shippingAmountBasisSelect = $toPgNumeric('shipping_amount_basis');
            $shippingCgstTaxSelect = $toPgNumeric('shipping_cgst_tax');
            $shippingSgstTaxSelect = $toPgNumeric('shipping_sgst_tax');
            $shippingUtgstTaxSelect = $toPgNumeric('shipping_utgst_tax');
            $shippingIgstTaxSelect = $toPgNumeric('shipping_igst_tax');
            $shippingCessTaxSelect = $toPgNumeric('shipping_cess_tax');
            $giftWrapAmountSelect = $toPgNumeric('gift_wrap_amount');
            $giftWrapAmountBasisSelect = $toPgNumeric('gift_wrap_amount_basis');
            $giftWrapCgstTaxSelect = $toPgNumeric('gift_wrap_cgst_tax');
            $giftWrapSgstTaxSelect = $toPgNumeric('gift_wrap_sgst_tax');
            $giftWrapUtgstTaxSelect = $toPgNumeric('gift_wrap_utgst_tax');
            $giftWrapIgstTaxSelect = $toPgNumeric('gift_wrap_igst_tax');
            $giftWrapCompensatoryCessTaxSelect = $toPgNumeric('gift_wrap_compensatory_cess_tax');
            $itemPromoDiscountSelect = $toPgNumeric('item_promo_discount');
            $itemPromoDiscountBasisSelect = $toPgNumeric('item_promo_discount_basis');
            $itemPromoTaxSelect = $toPgNumeric('item_promo_tax');
            $shippingPromoDiscountSelect = $toPgNumeric('shipping_promo_discount');
            $shippingPromoDiscountBasisSelect = $toPgNumeric('shipping_promo_discount_basis');
            $shippingPromoTaxSelect = $toPgNumeric('shipping_promo_tax');
            $giftWrapPromoDiscountSelect = $toPgNumeric('gift_wrap_promo_discount');
            $giftWrapPromoDiscountBasisSelect = $toPgNumeric('gift_wrap_promo_discount_basis');
            $giftWrapPromoTaxSelect = $toPgNumeric('gift_wrap_promo_tax');
            $tcsCgstRateSelect = $toPgNumeric('tcs_cgst_rate');
            $tcsCgstAmountSelect = $toPgNumeric('tcs_cgst_amount');
            $tcsSgstRateSelect = $toPgNumeric('tcs_sgst_rate');
            $tcsSgstAmountSelect = $toPgNumeric('tcs_sgst_amount');
            $tcsUtgstRateSelect = $toPgNumeric('tcs_utgst_rate');
            $tcsUtgstAmountSelect = $toPgNumeric('tcs_utgst_amount');
            $tcsIgstRateSelect = $toPgNumeric('tcs_igst_rate');
            $tcsIgstAmountSelect = $toPgNumeric('tcs_igst_amount');
            $warehouseIdSelect = "NULLIF(TRIM(BOTH FROM COALESCE(r.warehouse_id, '')), '')";
        }

        $inserted = DB::affectingStatement("
            INSERT INTO working_data (
                raw_data_file_id,
                b2b_b2c,
                seller_gstin,
                bill_from_city,
                bill_from_state,
                bill_from_country,
                bill_from_postal_code,
                ship_from_city,
                ship_from_state,
                ship_from_country,
                ship_from_postal_code,
                ship_to_city,
                ship_to_state,
                ship_to_country,
                ship_to_postal_code,
                invoice_number,
                invoice_date,
                customer_bill_to_gstid,
                buyer_name,
                bill_to_city,
                bill_to_state,
                transaction_type,
                for_sap,
                order_id,
                shipment_date,
                order_date,
                quantity,
                item_description,
                hsn_sac,
                sku,
                product_name,
                invoice_amount,
                tax_exclusive_gross_taxable_value,
                total_tax_amount,
                gst_rate,
                cgst,
                sgst,
                igst,
                total_gst,
                compensatory_cess_tax,
                shipping_amount,
                shipping_amount_basis,
                shipping_cgst_tax,
                shipping_sgst_tax,
                shipping_utgst_tax,
                shipping_igst_tax,
                shipping_cess_tax,
                gift_wrap_amount,
                gift_wrap_amount_basis,
                gift_wrap_cgst_tax,
                gift_wrap_sgst_tax,
                gift_wrap_utgst_tax,
                gift_wrap_igst_tax,
                gift_wrap_compensatory_cess_tax,
                item_promo_discount,
                item_promo_discount_basis,
                item_promo_tax,
                shipping_promo_discount,
                shipping_promo_discount_basis,
                shipping_promo_tax,
                gift_wrap_promo_discount,
                gift_wrap_promo_discount_basis,
                gift_wrap_promo_tax,
                tcs_cgst_rate,
                tcs_cgst_amount,
                tcs_sgst_rate,
                tcs_sgst_amount,
                tcs_utgst_rate,
                tcs_utgst_amount,
                tcs_igst_rate,
                tcs_igst_amount,
                warehouse_id,
                fulfillment_channel,
                payment_method_code,
                bill_to_country,
                bill_to_postalcode,
                customer_ship_to_gstid,
                receiver_gstin,
                credit_note_no,
                credit_note_date,
                irn_number,
                irn_filing_status,
                irn_date,
                irn_error_code,
                created_at,
                updated_at
            )
            SELECT
                r.raw_data_file_id,
                r.file_type,
                r.seller_gstin,
                r.bill_from_city,
                r.bill_from_state,
                r.bill_from_country,
                r.bill_from_postal_code,
                r.ship_from_city,
                r.ship_from_state,
                r.ship_from_country,
                r.ship_from_postal_code,
                r.ship_to_city,
                r.ship_to_state,
                r.ship_to_country,
                r.ship_to_postal_code,
                r.invoice_number,
                {$invoiceDateSelect},
                r.customer_bill_to_gstid,
                r.buyer_name,
                r.bill_to_city,
                r.bill_to_state,
                r.transaction_type,
                NULL,
                r.order_id,
                {$shipmentDateSelect},
                {$orderDateSelect},
                {$quantitySelect},
                r.item_description,
                r.hsn_sac,
                r.sku,
                r.product_name,
                {$invoiceAmountSelect},
                {$taxExclusiveGrossSelect},
                {$totalTaxAmountSelect},
                {$gstRateSelect},
                {$cgstSelect},
                {$sgstSelect},
                {$igstSelect},
                {$totalGstSelect},
                {$compensatoryCessTaxSelect},
                {$shippingAmountSelect},
                {$shippingAmountBasisSelect},
                {$shippingCgstTaxSelect},
                {$shippingSgstTaxSelect},
                {$shippingUtgstTaxSelect},
                {$shippingIgstTaxSelect},
                {$shippingCessTaxSelect},
                {$giftWrapAmountSelect},
                {$giftWrapAmountBasisSelect},
                {$giftWrapCgstTaxSelect},
                {$giftWrapSgstTaxSelect},
                {$giftWrapUtgstTaxSelect},
                {$giftWrapIgstTaxSelect},
                {$giftWrapCompensatoryCessTaxSelect},
                {$itemPromoDiscountSelect},
                {$itemPromoDiscountBasisSelect},
                {$itemPromoTaxSelect},
                {$shippingPromoDiscountSelect},
                {$shippingPromoDiscountBasisSelect},
                {$shippingPromoTaxSelect},
                {$giftWrapPromoDiscountSelect},
                {$giftWrapPromoDiscountBasisSelect},
                {$giftWrapPromoTaxSelect},
                {$tcsCgstRateSelect},
                {$tcsCgstAmountSelect},
                {$tcsSgstRateSelect},
                {$tcsSgstAmountSelect},
                {$tcsUtgstRateSelect},
                {$tcsUtgstAmountSelect},
                {$tcsIgstRateSelect},
                {$tcsIgstAmountSelect},
                {$warehouseIdSelect},
                r.fulfillment_channel,
                r.payment_method_code,
                r.bill_to_country,
                r.bill_to_postalcode,
                r.customer_ship_to_gstid,
                r.receiver_gstin,
                r.credit_note_no,
                {$creditNoteDateSelect},
                r.irn_number,
                r.irn_filing_status,
                {$irnDateSelect},
                r.irn_error_code,
                NOW(),
                NOW()
            FROM raw_data_imports r
            WHERE r.raw_data_file_id = {$id}
        ");

        if ($inserted <= 0) {
            return ['ok' => false, 'error' => 'Processing ran but inserted 0 rows.'];
        }

        DB::table('raw_data_files')->where('id', $id)->update([
            'processed_at' => now(),
            'rules_applied_at' => null,
            'status' => 'processed',
            'updated_at' => now(),
        ]);

        return ['ok' => true, 'inserted' => $inserted];
    }

    public function process($id)
    {
        $result = $this->processFileToWorkingData((int) $id);
        if (!($result['ok'] ?? false)) {
            return redirect()->route('raw-data-import')->with('error', $result['error'] ?? 'Processing failed.');
        }

        return redirect()->route('raw-data-import')->with(
            'message',
            'Processing complete. Inserted rows: '.($result['inserted'] ?? 0)
        );
    }

    /**
     * Run processing for every raw_data_file in the given calendar period (same as clicking Process per file).
     */
    public function processPeriod(Request $request)
    {
        $request->validate([
            'month' => 'required|integer|between:1,12',
            'year' => 'required|integer|min:2000|max:2100',
            'source_type' => 'nullable|string|max:64',
        ]);

        $month = (int) $request->input('month');
        $year = (int) $request->input('year');
        $sourceType = $request->input('source_type');

        $q = DB::table('raw_data_files')
            ->where('month', $month)
            ->where('year', $year);
        if ($sourceType === null || $sourceType === '') {
            $q->where(function ($q2) {
                $q2->whereNull('source_type')->orWhere('source_type', '');
            });
        } else {
            $q->where('source_type', $sourceType);
        }

        $files = $q->orderBy('id')->get();
        if ($files->isEmpty()) {
            return redirect()->route('raw-data-import')->with('error', 'No files found for this period.');
        }

        $totalInserted = 0;
        $errors = [];
        foreach ($files as $file) {
            $result = $this->processFileToWorkingData((int) $file->id);
            if (!($result['ok'] ?? false)) {
                $label = ($file->file_type ?? '?').' — '.($file->filename ?? 'file #'.$file->id);
                $errors[] = $label.': '.($result['error'] ?? 'failed');
            } else {
                $totalInserted += (int) ($result['inserted'] ?? 0);
            }
        }

        $redirectParams = array_filter([
            'month' => $month,
            'year' => $year,
            'source_type' => $sourceType,
        ], static fn ($v) => $v !== null && $v !== '');

        if ($errors !== []) {
            $detail = implode(' ', $errors);
            if ($totalInserted > 0) {
                return redirect()->route('raw-data-import.period', $redirectParams)->with(
                    'warning',
                    'Total rows inserted: '.$totalInserted.'. Some files failed: '.$detail
                );
            }

            return redirect()->route('raw-data-import.period', $redirectParams)->with('error', $detail);
        }

        return redirect()->route('raw-data-import.period', $redirectParams)->with(
            'message',
            'Processing complete for all files in this period. Total rows inserted: '.$totalInserted
        );
    }

    /**
     * Apply rules for all matching raw_data_files in a calendar period (same SQL as single-file Apply Rules).
     */
    public function applyRulesPeriod(Request $request)
    {
        
        if($request->amazon_b2b_file_type == 'b2b'){
            DB::table('working_data')
                ->where('b2b_b2c', 'b2b')
                ->whereRaw("LOWER(TRIM(COALESCE(transaction_type, ''))) IN ('cancel', 'cancelled', 'cancellation', 'cancle')")
                ->delete();
            DB::table('working_data')
                ->where('b2b_b2c', 'b2b')
                ->whereRaw("LOWER(REPLACE(REPLACE(TRIM(COALESCE(transaction_type, '')), '-', '_'), ' ', '')) = 'freereplacement'")
                ->delete();

            DB::table('working_data')
                ->where('b2b_b2c', 'b2b')
                ->whereRaw('COALESCE(total_gst, 0) <> COALESCE(total_tax_amount, 0)')
                ->update(['total_gst' => DB::raw('total_tax_amount'), 'updated_at' => now()]);

            DB::table('working_data')
            ->where('b2b_b2c', 'b2b')
            ->whereRaw('tax_exclusive_gross_taxable_value IS NOT NULL AND tax_exclusive_gross_taxable_value <> 0')
            ->update([
                'gst_rate' => DB::raw('ROUND((COALESCE(total_gst, 0) * 100) / tax_exclusive_gross_taxable_value, 0)'),
                'updated_at' => now(),
            ]);

            DB::table('working_data')
            ->where('b2b_b2c', 'b2b')
            ->whereRaw("LOWER(TRIM(COALESCE(transaction_type, ''))) = 'shipment'")
            ->update(['for_sap' => 'Sale', 'updated_at' => now()]);

            DB::table('working_data')
            ->where('b2b_b2c', 'b2b')
            ->whereRaw("LOWER(TRIM(COALESCE(transaction_type, ''))) = 'refund'")
            ->update(['for_sap' => 'Return', 'updated_at' => now()]);

        }

        if($request->amazon_b2c_file_type == 'b2c'){

             // Rule 1: remove cancel rows (this file only)
             DB::table('working_data')
             ->where('b2b_b2c', 'b2c')
             ->whereRaw("LOWER(TRIM(COALESCE(transaction_type, ''))) IN ('cancel', 'cancelled', 'cancellation', 'cancle')")
             ->delete();

            // Rule 2: Shipment -> for_sap Sale
            DB::table('working_data')
            ->where('b2b_b2c', 'b2c')
                ->whereRaw("LOWER(TRIM(COALESCE(transaction_type, ''))) = 'shipment'")
                ->update(['for_sap' => 'Sale', 'updated_at' => now()]);

            // Rule 3: Refund -> for_sap Return
            DB::table('working_data')
            ->where('b2b_b2c', 'b2c')
                ->whereRaw("LOWER(TRIM(COALESCE(transaction_type, ''))) = 'refund'")
                ->update(['for_sap' => 'Return', 'updated_at' => now()]);

            // Rule 4: align total_gst with total_tax_amount when they differ
            DB::table('working_data')
            ->where('b2b_b2c', 'b2c')
                ->whereRaw('COALESCE(total_gst, 0) <> COALESCE(total_tax_amount, 0)')
                ->update(['total_gst' => DB::raw('total_tax_amount'), 'updated_at' => now()]);

            // Rule 5: gst_rate = (total_gst * 100) / taxable value (SQL expression, not a string)
            DB::table('working_data')
            ->where('b2b_b2c', 'b2c')
                ->whereRaw('tax_exclusive_gross_taxable_value IS NOT NULL AND tax_exclusive_gross_taxable_value <> 0')
                ->update([
                    'gst_rate' => DB::raw('ROUND((COALESCE(total_gst, 0) * 100) / tax_exclusive_gross_taxable_value, 0)'),
                    'updated_at' => now(),
                ]);

            
           
        }
        
        if($request->amazon_stock_transfer_file_type == 'STOCK_TRANSFER'){
           
            DB::table('working_data')
            ->where('b2b_b2c', 'STOCK_TRANSFER')
            ->whereRaw("LOWER(TRIM(COALESCE(b2b_b2c, ''))) = 'stock_transfer'")
            ->whereRaw("
                LOWER(REPLACE(REPLACE(TRIM(COALESCE(transaction_type, '')), '-', '_'), ' ', '')) = 'fc_removal_cancel'
            ")
            ->delete();
 
            DB::table('working_data')
            ->where('b2b_b2c', 'STOCK_TRANSFER')
                ->whereRaw("LOWER(TRIM(COALESCE(b2b_b2c, ''))) = 'STOCK_TRANSFER'")
                ->whereRaw("TRIM(COALESCE(receiver_gstin, '')) <> ''")
                ->whereRaw("TRIM(COALESCE(seller_gstin, '')) <> ''")
                ->whereRaw("UPPER(TRIM(COALESCE(receiver_gstin, ''))) = UPPER(TRIM(COALESCE(seller_gstin, '')))")
            ->delete();
 
            DB::table('working_data')
            ->where('b2b_b2c', 'STOCK_TRANSFER')
                ->whereRaw("LOWER(TRIM(COALESCE(b2b_b2c, ''))) = 'stock_transfer'")
                ->whereNotNull('total_tax_amount')
                ->where('total_tax_amount', 0)
            ->delete();
        }
        return redirect()->route('raw-data-import')->with('message', 'Rules applied successfully');
    }

    /**
     * Core rule SQL for one raw_data_file (working_data rows). Does not update raw_data_files.
     *
     * @return array{deleted: int, updated: int, totalGstUpdated: int, totalGstCorrectionUpdated: int, gstRateUpdated: int}
     */
    private function executeApplyRulesForFile(int $id): array
    {
        $driver = DB::connection()->getDriverName();
        DB::beginTransaction();
        try {
            if ($driver === 'pgsql') {
                // Remove Cancel / FreeReplacement rows for this file (B2B and B2C).
                $deleted = DB::affectingStatement(
                    '
                        DELETE FROM working_data w
                        WHERE w.raw_data_file_id = ?
                          AND (
                                LOWER(TRIM(BOTH FROM COALESCE(w.transaction_type, \'\'))) IN (\'cancel\', \'cancelled\', \'cancellation\', \'cancle\')
                             OR LOWER(REPLACE(REPLACE(TRIM(BOTH FROM COALESCE(w.transaction_type, \'\')), \' \', \'\'), \'_\', \'\')) = \'freereplacement\'
                          )
                    ',
                    [$id]
                );

                $updated = DB::affectingStatement(
                    '
                        UPDATE working_data w
                        SET for_sap = CASE
                                WHEN LOWER(TRIM(BOTH FROM COALESCE(r.transaction_type, \'\'))) = \'shipment\' THEN \'Sale\'
                                WHEN LOWER(TRIM(BOTH FROM COALESCE(r.transaction_type, \'\'))) = \'refund\' THEN \'Return\'
                                ELSE w.for_sap
                            END,
                            updated_at = NOW()
                        FROM raw_data_imports r
                        WHERE w.raw_data_file_id = ?
                          AND r.raw_data_file_id = w.raw_data_file_id
                          AND LOWER(TRIM(BOTH FROM COALESCE(w.transaction_type, \'\'))) = LOWER(TRIM(BOTH FROM COALESCE(r.transaction_type, \'\')))
                          AND LOWER(TRIM(BOTH FROM COALESCE(r.transaction_type, \'\'))) IN (\'shipment\', \'refund\')
                    ',
                    [$id]
                );

                $totalGstUpdated = DB::affectingStatement(
                    '
                        UPDATE working_data w
                        SET total_gst = (
                            COALESCE(
                                CASE
                                    WHEN trim(both from coalesce(r.cgst_tax, \'\')) ~ \'^-?([0-9]+(\\.[0-9]*)?|\\.[0-9]+)$\'
                                    THEN trim(both from r.cgst_tax)::numeric
                                END,
                                0::numeric
                            )
                            + COALESCE(
                                CASE
                                    WHEN trim(both from coalesce(r.sgst_tax, \'\')) ~ \'^-?([0-9]+(\\.[0-9]*)?|\\.[0-9]+)$\'
                                    THEN trim(both from r.sgst_tax)::numeric
                                END,
                                0::numeric
                            )
                            + COALESCE(
                                CASE
                                    WHEN trim(both from coalesce(r.utgst_tax, \'\')) ~ \'^-?([0-9]+(\\.[0-9]*)?|\\.[0-9]+)$\'
                                    THEN trim(both from r.utgst_tax)::numeric
                                END,
                                0::numeric
                            )
                            + COALESCE(
                                CASE
                                    WHEN trim(both from coalesce(r.igst_tax, \'\')) ~ \'^-?([0-9]+(\\.[0-9]*)?|\\.[0-9]+)$\'
                                    THEN trim(both from r.igst_tax)::numeric
                                END,
                                0::numeric
                            )
                        ),
                            updated_at = NOW()
                        FROM raw_data_imports r
                        WHERE w.raw_data_file_id = ?
                          AND r.raw_data_file_id = w.raw_data_file_id
                          AND LOWER(COALESCE(w.b2b_b2c, \'\')) = \'b2c\'
                          AND COALESCE(w.invoice_number, \'\') = COALESCE(r.invoice_number, \'\')
                          AND COALESCE(w.order_id, \'\') = COALESCE(r.order_id, \'\')
                          AND COALESCE(w.transaction_type, \'\') = COALESCE(r.transaction_type, \'\')
                          AND COALESCE(w.sku, \'\') = COALESCE(r.sku, \'\')
                          AND COALESCE(to_char(w.invoice_date, \'YYYY-MM-DD\'), \'\') = COALESCE(
                                CASE
                                    WHEN trim(both from coalesce(r.invoice_date, \'\')) = \'\' THEN NULL
                                    WHEN trim(both from r.invoice_date) ~ \'^[0-9]{4}-[0-9]{2}-[0-9]{2}$\'
                                        THEN to_char(to_date(trim(both from r.invoice_date), \'YYYY-MM-DD\'), \'YYYY-MM-DD\')
                                    WHEN trim(both from r.invoice_date) ~ \'^[0-9]{2}-[0-9]{2}-[0-9]{4}$\'
                                        THEN to_char(to_date(trim(both from r.invoice_date), \'DD-MM-YYYY\'), \'YYYY-MM-DD\')
                                    WHEN trim(both from r.invoice_date) ~ \'^[0-9]{2}/[0-9]{2}/[0-9]{4}$\'
                                        THEN to_char(to_date(trim(both from r.invoice_date), \'DD/MM/YYYY\'), \'YYYY-MM-DD\')
                                    WHEN trim(both from r.invoice_date) ~ \'^[0-9]{1,2}/[0-9]{1,2}/[0-9]{4}( [0-9]{1,2}:[0-9]{2}(:[0-9]{2})?)?$\'
                                        THEN to_char(to_date(split_part(trim(both from r.invoice_date), \' \', 1), \'FMMM/FMDD/YYYY\'), \'YYYY-MM-DD\')
                                    ELSE NULL
                                END,
                                \'\'
                            )
                          AND COALESCE(w.quantity::text, \'\') = COALESCE(r.quantity, \'\')
                    ',
                    [$id]
                );

                $totalGstCorrectionUpdated = DB::affectingStatement(
                    '
                        UPDATE working_data
                        SET total_gst = total_tax_amount,
                            updated_at = NOW()
                        WHERE raw_data_file_id = ?
                          AND LOWER(COALESCE(b2b_b2c, \'\')) = \'b2c\'
                          AND COALESCE(total_gst, 0) <> COALESCE(total_tax_amount, 0)
                    ',
                    [$id]
                );

                $gstRateUpdated = DB::affectingStatement(
                    '
                        UPDATE working_data w
                        SET gst_rate = CASE
                                WHEN w.total_gst IS NOT NULL
                                    AND w.tax_exclusive_gross_taxable_value IS NOT NULL
                                    AND w.tax_exclusive_gross_taxable_value <> 0
                                THEN ROUND(
                                    (
                                        (w.total_gst * 100)
                                        / w.tax_exclusive_gross_taxable_value
                                    ),
                                    0
                                )
                                ELSE w.gst_rate
                            END,
                            updated_at = NOW()
                        WHERE w.raw_data_file_id = ?
                    ',
                    [$id]
                );
            } else {
                // MySQL/MariaDB: remove Cancel / FreeReplacement rows for this file (B2B and B2C).
                $deleted = DB::affectingStatement(
                    '
                        DELETE w
                        FROM working_data w
                        WHERE w.raw_data_file_id = ?
                          AND (
                                LOWER(TRIM(COALESCE(w.transaction_type, \'\'))) IN (\'cancel\', \'cancelled\', \'cancellation\', \'cancle\')
                             OR LOWER(REPLACE(REPLACE(TRIM(COALESCE(w.transaction_type, \'\')), \' \', \'\'), \'_\', \'\')) = \'freereplacement\'
                          )
                    ',
                    [$id]
                );

                $updated = DB::affectingStatement(
                    '
                        UPDATE working_data w
                        INNER JOIN raw_data_imports r
                          ON r.raw_data_file_id = w.raw_data_file_id
                         AND LOWER(TRIM(COALESCE(r.transaction_type, \'\'))) = LOWER(TRIM(COALESCE(w.transaction_type, \'\')))
                        SET w.for_sap = CASE
                                WHEN LOWER(TRIM(COALESCE(r.transaction_type, \'\'))) = \'shipment\' THEN \'Sale\'
                                WHEN LOWER(TRIM(COALESCE(r.transaction_type, \'\'))) = \'refund\' THEN \'Return\'
                                ELSE w.for_sap
                            END,
                            w.updated_at = NOW()
                        WHERE w.raw_data_file_id = ?
                          AND LOWER(TRIM(COALESCE(r.transaction_type, \'\'))) IN (\'shipment\', \'refund\')
                    ',
                    [$id]
                );

                $totalGstUpdated = DB::affectingStatement(
                    '
                        UPDATE working_data w
                        INNER JOIN raw_data_imports r
                          ON r.raw_data_file_id = w.raw_data_file_id
                         AND COALESCE(r.invoice_number, \'\') = COALESCE(w.invoice_number, \'\')
                         AND COALESCE(r.order_id, \'\') = COALESCE(w.order_id, \'\')
                         AND COALESCE(r.transaction_type, \'\') = COALESCE(w.transaction_type, \'\')
                         AND COALESCE(r.sku, \'\') = COALESCE(w.sku, \'\')
                         AND COALESCE(r.invoice_date, \'\') = COALESCE(w.invoice_date, \'\')
                         AND COALESCE(r.quantity, \'\') = COALESCE(w.quantity, \'\')
                        SET w.total_gst = CAST(
                            IF(TRIM(COALESCE(r.cgst_tax, \'\')) REGEXP \'^-?[0-9]+(\\.[0-9]+)?$\', CAST(TRIM(r.cgst_tax) AS DECIMAL(18, 4)), 0)
                            + IF(TRIM(COALESCE(r.sgst_tax, \'\')) REGEXP \'^-?[0-9]+(\\.[0-9]+)?$\', CAST(TRIM(r.sgst_tax) AS DECIMAL(18, 4)), 0)
                            + IF(TRIM(COALESCE(r.utgst_tax, \'\')) REGEXP \'^-?[0-9]+(\\.[0-9]+)?$\', CAST(TRIM(r.utgst_tax) AS DECIMAL(18, 4)), 0)
                            + IF(TRIM(COALESCE(r.igst_tax, \'\')) REGEXP \'^-?[0-9]+(\\.[0-9]+)?$\', CAST(TRIM(r.igst_tax) AS DECIMAL(18, 4)), 0)
                            AS CHAR
                        ),
                        w.updated_at = NOW()
                        WHERE w.raw_data_file_id = ?
                          AND LOWER(COALESCE(w.b2b_b2c, \'\')) = \'b2c\'
                    ',
                    [$id]
                );

                $totalGstCorrectionUpdated = DB::affectingStatement(
                    '
                        UPDATE working_data
                        SET total_gst = total_tax_amount,
                            updated_at = NOW()
                        WHERE raw_data_file_id = ?
                          AND LOWER(COALESCE(b2b_b2c, \'\')) = \'b2c\'
                          AND COALESCE(total_gst, 0) <> COALESCE(total_tax_amount, 0)
                    ',
                    [$id]
                );

                $gstRateUpdated = DB::affectingStatement(
                    '
                        UPDATE working_data w
                        SET w.gst_rate = ROUND(
                            (
                                IF(REPLACE(TRIM(COALESCE(w.total_gst, \'\')), \',\', \'\') REGEXP \'^-?[0-9]+(\\.[0-9]+)?$\', CAST(REPLACE(TRIM(w.total_gst), \',\', \'\') AS DECIMAL(18, 6)), NULL)
                                * 100
                            ) / NULLIF(
                                IF(REPLACE(TRIM(COALESCE(w.tax_exclusive_gross_taxable_value, \'\')), \',\', \'\') REGEXP \'^-?[0-9]+(\\.[0-9]+)?$\', CAST(REPLACE(TRIM(w.tax_exclusive_gross_taxable_value), \',\', \'\') AS DECIMAL(18, 6)), NULL),
                                0
                            ),
                            0
                        ),
                        w.updated_at = NOW()
                        WHERE w.raw_data_file_id = ?
                    ',
                    [$id]
                );
            }

            DB::commit();
        } catch (\Throwable $exception) {
            DB::rollBack();
            throw $exception;
        }

        return [
            'deleted' => $deleted,
            'updated' => $updated,
            'totalGstUpdated' => $totalGstUpdated,
            'totalGstCorrectionUpdated' => $totalGstCorrectionUpdated,
            'gstRateUpdated' => $gstRateUpdated,
        ];
    }

    public function applyRules($id)
    {
        $file = DB::table('raw_data_files')->where('id', $id)->first();
        if (!$file) {
            return redirect()->route('raw-data-import')->with('error', 'File record not found.');
        }

        $workingCount = DB::table('working_data')->where('raw_data_file_id', $id)->count();
        if ($workingCount === 0) {
            return redirect()->route('raw-data-import')->with(
                'error',
                'No working rows found for this file. Please click Processing first, then Apply Rules.'
            );
        }

        $stats = $this->executeApplyRulesForFile((int) $id);

        DB::table('raw_data_files')->where('id', $id)->update([
            'rules_applied_at' => now(),
            'status' => 'rule_applied',
            'updated_at' => now(),
        ]);

        return redirect()->route('raw-data-import')->with(
            'message',
            'Rules applied. Removed Cancel/FreeReplacement rows: ' . $stats['deleted']
            . ', for_sap updated: ' . $stats['updated']
            . ', total_gst updated: ' . $stats['totalGstUpdated']
            . ', total_gst corrected: ' . $stats['totalGstCorrectionUpdated']
            . ', gst_rate updated: ' . $stats['gstRateUpdated']
        );
    }

    public function downloadWorkingData($id)
    {
        $file = DB::table('raw_data_files')->where('id', $id)->first();
        if (!$file) {
            return redirect()->route('raw-data-import')->with('error', 'File record not found.');
        }

        $rows = DB::table('working_data')
            ->where('raw_data_file_id', $id)
            ->orderBy('id')
            ->get();

        if ($rows->isEmpty()) {
            return redirect()->route('raw-data-import')->with(
                'error',
                'No working rows found for this file. Please click Processing first, then Apply Rules.'
            );
        }

        // Export all working columns except timestamps.
        $columns = [
            'b2b_b2c',
            'seller_gstin',
            'bill_from_city',
            'bill_from_state',
            'bill_from_country',
            'bill_from_postal_code',
            'ship_from_city',
            'ship_from_state',
            'ship_from_country',
            'ship_from_postal_code',
            'ship_to_city',
            'ship_to_state',
            'ship_to_country',
            'ship_to_postal_code',
            'invoice_number',
            'invoice_date',
            'customer_bill_to_gstid',
            'buyer_name',
            'bill_to_city',
            'bill_to_state',
            'transaction_type',
            'for_sap',
            'order_id',
            'shipment_date',
            'order_date',
            'quantity',
            'item_description',
            'hsn_sac',
            'sku',
            'product_name',
            'invoice_amount',
            'tax_exclusive_gross_taxable_value',
            'total_tax_amount',
            'gst_rate',
            'cgst',
            'sgst',
            'igst',
            'total_gst',
            'compensatory_cess_tax',
            'shipping_amount',
            'shipping_amount_basis',
            'shipping_cgst_tax',
            'shipping_sgst_tax',
            'shipping_utgst_tax',
            'shipping_igst_tax',
            'shipping_cess_tax',
            'gift_wrap_amount',
            'gift_wrap_amount_basis',
            'gift_wrap_cgst_tax',
            'gift_wrap_sgst_tax',
            'gift_wrap_utgst_tax',
            'gift_wrap_igst_tax',
            'gift_wrap_compensatory_cess_tax',
            'item_promo_discount',
            'item_promo_discount_basis',
            'item_promo_tax',
            'shipping_promo_discount',
            'shipping_promo_discount_basis',
            'shipping_promo_tax',
            'gift_wrap_promo_discount',
            'gift_wrap_promo_discount_basis',
            'gift_wrap_promo_tax',
            'tcs_cgst_rate',
            'tcs_cgst_amount',
            'tcs_sgst_rate',
            'tcs_sgst_amount',
            'tcs_utgst_rate',
            'tcs_utgst_amount',
            'tcs_igst_rate',
            'tcs_igst_amount',
            'warehouse_id',
            'fulfillment_channel',
            'payment_method_code',
            'bill_to_country',
            'bill_to_postalcode',
            'customer_ship_to_gstid',
            'receiver_gstin',
            'credit_note_no',
            'credit_note_date',
            'irn_number',
            'irn_filing_status',
            'irn_date',
            'irn_error_code',
        ];

        $numericColumns = array_flip([
            'quantity',
            'invoice_amount',
            'tax_exclusive_gross_taxable_value',
            'total_tax_amount',
            'gst_rate',
            'cgst',
            'sgst',
            'igst',
            'total_gst',
            'compensatory_cess_tax',
            'shipping_amount',
            'shipping_amount_basis',
            'shipping_cgst_tax',
            'shipping_sgst_tax',
            'shipping_utgst_tax',
            'shipping_igst_tax',
            'shipping_cess_tax',
            'gift_wrap_amount',
            'gift_wrap_amount_basis',
            'gift_wrap_cgst_tax',
            'gift_wrap_sgst_tax',
            'gift_wrap_utgst_tax',
            'gift_wrap_igst_tax',
            'gift_wrap_compensatory_cess_tax',
            'item_promo_discount',
            'item_promo_discount_basis',
            'item_promo_tax',
            'shipping_promo_discount',
            'shipping_promo_discount_basis',
            'shipping_promo_tax',
            'gift_wrap_promo_discount',
            'gift_wrap_promo_discount_basis',
            'gift_wrap_promo_tax',
            'tcs_cgst_rate',
            'tcs_cgst_amount',
            'tcs_sgst_rate',
            'tcs_sgst_amount',
            'tcs_utgst_rate',
            'tcs_utgst_amount',
            'tcs_igst_rate',
            'tcs_igst_amount',
        ]);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header row: human-readable labels (e.g. seller_gstin -> Seller Gstin)
        $colIndex = 1;
        foreach ($columns as $column) {
            $sheet->setCellValueByColumnAndRow($colIndex, 1, Str::headline($column));
            $colIndex++;
        }

        // Data rows
        $rowIndex = 2;
        foreach ($rows as $row) {
            $colIndex = 1;
            foreach ($columns as $column) {
                $value = $row->{$column} ?? null;

                if (isset($numericColumns[$column])) {
                    $clean = str_replace(',', '', trim((string) ($value ?? '')));
                    if ($clean !== '' && is_numeric($clean)) {
                        $numericValue = strpos($clean, '.') === false ? (int) $clean : (float) $clean;
                        $sheet->setCellValueExplicitByColumnAndRow(
                            $colIndex,
                            $rowIndex,
                            $numericValue,
                            DataType::TYPE_NUMERIC
                        );
                        // Always show 2 decimal places in Excel exports (e.g. 18.00).
                        $sheet->getStyleByColumnAndRow($colIndex, $rowIndex)
                            ->getNumberFormat()
                            ->setFormatCode('0.00');
                    } else {
                        $sheet->setCellValueByColumnAndRow($colIndex, $rowIndex, '-');
                    }
                } else {
                    $textValue = trim((string) ($value ?? ''));
                    $sheet->setCellValueByColumnAndRow($colIndex, $rowIndex, $textValue === '' ? '-' : $textValue);
                }

                $colIndex++;
            }
            $rowIndex++;
        }

        $fileName = 'working_data_' . $id . '.xlsx';

        return response()->streamDownload(
            function () use ($spreadsheet) {
                $writer = new Xlsx($spreadsheet);
                $writer->save('php://output');
            },
            $fileName,
            ['Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet']
        );
    }

    private function importRawDataRows(string $path, int $rawDataFileId, string $sourceType, string $fileType): void
    {
        $driver = DB::connection()->getDriverName();

        $peekHandle = fopen($path, 'rb');
        if ($peekHandle === false) {
            throw new \RuntimeException('Unable to open import file.');
        }
        $firstRow = fgetcsv($peekHandle);
        fclose($peekHandle);

        if ($firstRow === false) {
            return;
        }

        $headerMap = self::buildImportHeaderToIndexMap($firstRow);
        $useHeaderMapping = count($headerMap) >= self::MIN_IMPORT_HEADER_MATCHES;

        $isStockTransfer = strcasecmp($fileType, self::FILE_TYPE_STOCK_TRANSFER) === 0;

        // COPY maps CSV columns to IMPORT_COLUMNS by fixed position only. Skip when row 1 maps enough
        // known headers (any column order) or when stock-transfer layout may differ from B2B order.
        if ($driver === 'pgsql' && !$isStockTransfer && !$useHeaderMapping) {
            try {
                $this->importWithPostgresCopy($path, $rawDataFileId, $sourceType, $fileType);
                return;
            } catch (\Throwable $exception) {
                // Fallback for environments where PostgreSQL service cannot access storage path
                // or when the CSV format doesn't match COPY options.
                Log::warning('Postgres COPY failed; falling back to CSV insert batch.', [
                    'raw_data_file_id' => $rawDataFileId,
                    'source_type' => $sourceType,
                    'file_type' => $fileType,
                    'path' => $path,
                    'error' => $exception->getMessage(),
                ]);
            }
        }

        $this->importWithCsvReader(
            $path,
            $rawDataFileId,
            $sourceType,
            $fileType,
            $useHeaderMapping ? $headerMap : null
        );
    }

    /**
     * Normalize a spreadsheet header cell to a snake_case key comparable to IMPORT_COLUMNS.
     */
    private static function normalizeImportHeaderLabel(string $raw): string
    {
        $s = trim($raw);
        if ($s !== '' && str_starts_with($s, "\xEF\xBB\xBF")) {
            $s = trim(substr($s, 3));
        }
        // Excel sometimes uses non-breaking spaces in headers
        $s = str_replace("\xC2\xA0", ' ', $s);
        $s = trim($s);
        $s = strtolower($s);
        $s = preg_replace('/[\s\-\.\/\(\):#]+/', '_', $s) ?? '';
        $s = preg_replace('/_+/', '_', $s) ?? '';
        $s = trim($s, '_');
        $s = preg_replace('/[^a-z0-9_]/', '', $s) ?? '';
        // Strip leading "1_", "12_" numbering sometimes pasted into row 1
        $s = preg_replace('/^\d+_/', '', $s) ?? '';

        return $s;
    }

    /**
     * Resolve a header label to an IMPORT_COLUMNS name, or null if unknown.
     */
    private static function headerLabelToImportColumn(string $raw): ?string
    {
        $key = self::normalizeImportHeaderLabel($raw);
        if ($key === '') {
            return null;
        }

        if (isset(self::IMPORT_HEADER_ALIASES[$key])) {
            $key = self::IMPORT_HEADER_ALIASES[$key];
        }

        static $valid = null;
        if ($valid === null) {
            $valid = array_flip(self::IMPORT_COLUMNS);
        }

        return isset($valid[$key]) ? $key : null;
    }

    /**
     * @return array<string, int> import column name => 0-based CSV column index
     */
    private static function buildImportHeaderToIndexMap(array $headerCells): array
    {
        $map = [];
        foreach ($headerCells as $index => $label) {
            $column = self::headerLabelToImportColumn((string) $label);
            if ($column !== null && !array_key_exists($column, $map)) {
                $map[$column] = (int) $index;
            }
        }

        return $map;
    }

    private function importWithPostgresCopy(string $path, int $rawDataFileId, string $sourceType, string $fileType): void
    {
        $tempTable = 'tmp_raw_data_imports_' . Str::lower(Str::random(10));
        $quotedColumns = implode(', ', array_map(static fn ($column) => '"' . $column . '"', self::IMPORT_COLUMNS));
        $tempColumns = implode(', ', array_map(static fn ($column) => '"' . $column . '" text', self::IMPORT_COLUMNS));
        $normalizedPath = str_replace('\\', '/', $path);
        $copyPath = str_replace("'", "''", $normalizedPath);

        DB::beginTransaction();
        try {
            DB::statement('CREATE TEMP TABLE "' . $tempTable . '" (' . $tempColumns . ') ON COMMIT DROP');
            DB::statement(
                'COPY "' . $tempTable . '" (' . $quotedColumns . ') FROM \'' . $copyPath . '\' WITH (FORMAT csv, HEADER true, DELIMITER \',\', QUOTE \'"\')'
            );

            $sql = '
                INSERT INTO raw_data_imports
                (raw_data_file_id, source_type, file_type, ' . $quotedColumns . ', created_at, updated_at)
                SELECT ?, ?, ?, ' . $quotedColumns . ', NOW(), NOW()
                FROM "' . $tempTable . '"
            ';
            DB::insert($sql, [$rawDataFileId, $sourceType, $fileType]);

            DB::commit();
        } catch (\Throwable $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    /**
     * @param  array<string, int>|null  $headerToIndex  Map IMPORT_COLUMNS name => 0-based file column index; null = positional (column order matches IMPORT_COLUMNS after skipping row 1).
     */
    private function importWithCsvReader(
        string $path,
        int $rawDataFileId,
        string $sourceType,
        string $fileType,
        ?array $headerToIndex = null
    ): void {
        $handle = fopen($path, 'rb');
        if ($handle === false) {
            throw new \RuntimeException('Unable to open import file.');
        }

        // Postgres has a hard limit on the number of bind parameters per query.
        // Since this fallback uses `insert($rows)` with many columns, we must chunk safely.
        $baseColumns = 5 + count(self::IMPORT_COLUMNS); // raw_data_file_id, source_type, file_type, created_at, updated_at + import columns
        // Keep a larger margin below 65535 because Laravel's query builder can
        // generate slightly more bind parameters than our simple estimate.
        $safeMaxParams = 20000;
        $chunkSize = max(1, intdiv($safeMaxParams, max(1, $baseColumns)));

        // Row 1 is always the header row for these exports; data starts at row 2.
        if (fgetcsv($handle) === false) {
            fclose($handle);

            return;
        }

        $rows = [];
        while (($values = fgetcsv($handle)) !== false) {
            if ($values === [null] || $values === []) {
                continue;
            }

            $row = [
                'raw_data_file_id' => $rawDataFileId,
                'source_type' => $sourceType,
                'file_type' => $fileType,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            if ($headerToIndex !== null) {
                foreach (self::IMPORT_COLUMNS as $column) {
                    $idx = $headerToIndex[$column] ?? null;
                    $row[$column] = ($idx !== null && array_key_exists($idx, $values))
                        ? $values[$idx]
                        : null;
                }
            } else {
                foreach (self::IMPORT_COLUMNS as $index => $column) {
                    $row[$column] = array_key_exists($index, $values) ? $values[$index] : null;
                }
            }

            $rows[] = $row;

            if (count($rows) >= $chunkSize) {
                DB::table('raw_data_imports')->insert($rows);
                $rows = [];
            }
        }

        fclose($handle);

        if (!empty($rows)) {
            DB::table('raw_data_imports')->insert($rows);
        }
    }

    public function destroy($id)
    {
        $file = DB::table('raw_data_files')->where('id', $id)->first();
        if (!$file) {
            return redirect()->route('raw-data-import')->with('error', 'File record not found.');
        }

        $this->deleteRawDataFileRecord((int) $id);

        return redirect()->route('raw-data-import')->with('message', 'File and related import/working data removed.');
    }

    /**
     * Delete one raw_data_files row, related imports/working, and stored files on disk.
     */
    private function deleteRawDataFileRecord(int $id): void
    {
        $file = DB::table('raw_data_files')->where('id', $id)->first();
        if (!$file) {
            return;
        }

        DB::transaction(function () use ($id) {
            DB::table('working_data')->where('raw_data_file_id', $id)->delete();
            DB::table('raw_data_imports')->where('raw_data_file_id', $id)->delete();
            DB::table('raw_data_files')->where('id', $id)->delete();
        });

        $appDir = storage_path('app');
        if (!empty($file->stored_derived_csv)) {
            File::delete($appDir . DIRECTORY_SEPARATOR . $file->stored_derived_csv);
        }

        if (!empty($file->stored_upload)) {
            File::delete($appDir . DIRECTORY_SEPARATOR . $file->stored_upload);
        }
    }

    /**
     * True if raw_data_files already has a row for this calendar month with the same normalized file and source type.
     */
    private function importCombinationExists(int $month, int $year, string $fileType, string $sourceType): bool
    {
        $normFt = $this->normalizeFileTypeForDuplicateKey($fileType);
        $normSt = $this->normalizeSourceTypeForDuplicateKey($sourceType);
        if ($normFt === '' || $normSt === '') {
            return false;
        }

        $rows = DB::table('raw_data_files')
            ->where('month', $month)
            ->where('year', $year)
            ->get(['file_type', 'source_type']);

        foreach ($rows as $row) {
            if ($this->normalizeFileTypeForDuplicateKey($row->file_type ?? '') === $normFt
                && $this->normalizeSourceTypeForDuplicateKey($row->source_type ?? '') === $normSt) {
                return true;
            }
        }

        return false;
    }

    private function normalizeFileTypeForDuplicateKey(?string $fileType): string
    {
        $ft = trim((string) $fileType);
        if ($ft === '') {
            return '';
        }
        if (strcasecmp($ft, 'STOCK_TRANSFER') === 0) {
            return 'STOCK_TRANSFER';
        }

        return strtolower($ft);
    }

    private function normalizeSourceTypeForDuplicateKey(?string $sourceType): string
    {
        $s = trim((string) $sourceType);
        if ($s === '') {
            return '';
        }

        return match (strtolower($s)) {
            'amazon' => 'Amazon',
            'blinkit' => 'Blinkit',
            'flipkart' => 'Flipkart',
            default => $s,
        };
    }
}

