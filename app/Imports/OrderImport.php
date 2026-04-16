<?php



namespace App\Imports;



use App\Models\Cities;

use App\Models\Countries;

use App\Models\ManageOrder;

use App\Models\OrderDate;

use App\Models\OrderItem;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Log;

use Maatwebsite\Excel\Concerns\ToModel;

use Maatwebsite\Excel\Concerns\WithHeadingRow;



class OrderImport implements ToModel, WithHeadingRow

{

    protected $errors = [];

    protected $successCount = 0;  // Track successful records

    protected $errorRows = [];   // Store errors for invalid rows

    protected $existingVins = []; // Cache existing VINs for optimization



    public function __construct()

    {

        // Preload existing VINs for duplicate checks

        $this->existingVins = ManageOrder::pluck('vin_record_number')->toArray();

    }



    public function model(array $row)

    {

        // Skip empty rows

        if (empty(array_filter($row))) {

            return null;

        }

        

        // Start transaction

        DB::beginTransaction();



        try {

            $this->errors = []; // Reset errors for the current row



            // Validate VIN Record ID (Check for null or empty value)

            if (empty($row['vin_vin_record_id'])) {

                $this->errors[] = "VIN Record Number is required and cannot be null or empty.";

            } elseif (in_array($row['vin_vin_record_id'], $this->existingVins)) {

                $this->errors[] = "Duplicate VIN Record Number: {$row['vin_vin_record_id']}";

            }



            // Validate Country

            $country = Countries::where('country_name', $row['country'])->first();

            if (!$country) {

                $this->errors[] = "Invalid country: {$row['country']}";

            }



            // Validate City

            $city = Cities::where('city_name', $row['state'])->first();

            if (!$city) {

                $this->errors[] = "Invalid city: {$row['state']}";

            }



            // Validate Order Item

            $orderItem = OrderItem::where('title', $row['order_item'])->first();

            if (!$orderItem) {

                $this->errors[] = "Invalid order item: {$row['order_item']}";

            }



            // If errors exist, log them and stop the process

            if (!empty($this->errors)) {

                $this->errorRows[] = [

                    'row_number' => $row['row_number'] ?? 'N/A', // Include row number if available

                    'row_data' => $row,

                    'errors' => $this->errors,

                ];

                DB::rollBack();

                return null;

            }



            // Create ManageOrder entry

            $manageOrder = ManageOrder::create([

                'country_id' => $country->id,

                'city_id' => $city->id,

                'order_item' => $orderItem->id,

                'email' => $row['email_address'],

                'address' => $row['address'],

                'location' => $row['location'],

                'order_note' => $row['order_note'],

                'quantity' => $row['quantity'],

                'mobile_number' => $row['mobile_phone'],

                'client_name' => $row['first_name'],

                // 'status' => 'Delivered',
                'status' => 'Pending',

                'associate_status'          => 1,
                // 'coustomer_service_status'  => 1,
                // 'warehouse_team_status'     => 1,
                // 'dispatch_team_status'      => 1,

                // 'assign_driver_user_id'  => 10,

                'vin_record_number' => $row['vin_vin_record_id'],

                'client_last_name' => $row['last_name'],

                'zip_code' => $row['postal_code_zip'],

                'brand' => $row['brand'],

                'description' => $row['model_description'],

                'branch' => $row['branch'],

                'engine_type' => $row['engine_type'],

                'transmission' => $row['transmission'],

                'invoice_date' => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['invoice_date'])->format('Y-m-d'),

                // 'delivered_date'    => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['delivery_date'])->format('Y-m-d'),

                'vin_number' => $row['vin_number'],

                'base_model' => $row['base_model'],

            ]);



            // Add VIN to cache to prevent duplicates in the same import

            $this->existingVins[] = $row['vin_vin_record_id'];



            // Create OrderDate entry

            OrderDate::create([

                'user_id' => '', // Specify user ID if applicable

                'order_status' => 'Pending',

                'date' => now()->format('d-m-Y h:i A'),

                'order_id' => $manageOrder->id,

            ]);



            DB::commit(); // Commit the transaction

            $this->successCount++;

            return $manageOrder;



        } catch (\Exception $e) {

            // Handle exception and rollback transaction

            DB::rollBack();



            // Log error

            Log::error("Error processing row: " . $e->getMessage());



            // Stop processing further rows and log the failure

            throw new \Exception("Processing stopped. {$this->successCount} rows successfully submitted. Error: " . $e->getMessage());

        }

    }



    // Return errors after processing

    public function getErrors()

    {

        return $this->errorRows;

    }



    // Return the count of successful rows

    public function getSuccessCount()

    {

        return $this->successCount;

    }



    // Return summary of the import process

    public function getSummary()

    {

        return [

            'success' => $this->successCount,

            'errors'  => $this->errorRows,

        ];

    }

}

