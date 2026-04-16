<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DummyDataController extends Controller
{
    private const CONTACT_COLUMNS = [
        'id',
        'name',
        'mobile',
        'email',
        'city',
        'state',
        'age',
        'salary',
        'gender',
        'status',
    ];

    public function index()
    {
        return view('dummy_data.index');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt',
        ]);

        $file = $request->file('file');

        $name = time() . '.csv';
        $file->move(storage_path('app'), $name);

        $path = storage_path('app/' . $name);
        $this->importContacts($path);

        return redirect()->route('dummy-data')->with('message', 'Import Done');
    }

    private function importContacts(string $path): void
    {
        $driver = DB::connection()->getDriverName();

        if ($driver === 'pgsql') {
            try {
                $this->importContactsWithPostgresCopy($path);
                return;
            } catch (\Throwable $exception) {
                // Fallback for environments where PostgreSQL service cannot access storage path.
            }
        }

        $this->importContactsWithCsvReader($path);
    }

    private function importContactsWithPostgresCopy(string $path): void
    {
        $tempTable = 'tmp_contacts_import_' . Str::lower(Str::random(10));
        $quotedColumns = implode(', ', array_map(static fn ($column) => '"' . $column . '"', self::CONTACT_COLUMNS));
        $tempColumns = implode(', ', array_map(static fn ($column) => '"' . $column . '" text', self::CONTACT_COLUMNS));
        $normalizedPath = str_replace('\\', '/', $path);
        $copyPath = str_replace("'", "''", $normalizedPath);

        DB::beginTransaction();
        try {
            DB::statement('CREATE TEMP TABLE "' . $tempTable . '" (' . $tempColumns . ') ON COMMIT DROP');
            DB::statement(
                'COPY "' . $tempTable . '" (' . $quotedColumns . ') FROM \'' . $copyPath . '\' WITH (FORMAT csv, HEADER true, DELIMITER \',\', QUOTE \'"\')'
            );
            DB::statement(
                'INSERT INTO contacts ("id", "name", "mobile", "email", "city", "state", "age", "salary", "gender", "status", "created_at")
                 SELECT NULLIF("id", \'\')::bigint, "name", "mobile", "email", "city", "state", "age", "salary", "gender", "status", NOW()
                 FROM "' . $tempTable . '"'
            );
            DB::statement(
                "SELECT setval(pg_get_serial_sequence('contacts', 'id'), COALESCE(MAX(id), 1), true) FROM contacts"
            );
            DB::commit();
        } catch (\Throwable $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    private function importContactsWithCsvReader(string $path): void
    {
        $handle = fopen($path, 'rb');
        if ($handle === false) {
            throw new \RuntimeException('Unable to open import file.');
        }

        // Skip CSV header row.
        fgetcsv($handle);

        $rows = [];
        while (($values = fgetcsv($handle)) !== false) {
            if ($values === [null] || $values === []) {
                continue;
            }

            $rows[] = [
                'id' => isset($values[0]) && $values[0] !== '' ? (int) $values[0] : null,
                'name' => $values[1] ?? null,
                'mobile' => $values[2] ?? null,
                'email' => $values[3] ?? null,
                'city' => $values[4] ?? null,
                'state' => $values[5] ?? null,
                'age' => $values[6] ?? null,
                'salary' => $values[7] ?? null,
                'gender' => $values[8] ?? null,
                'status' => $values[9] ?? null,
                'created_at' => now(),
            ];

            if (count($rows) >= 1000) {
                DB::table('contacts')->insert($rows);
                $rows = [];
            }
        }

        fclose($handle);

        if (!empty($rows)) {
            DB::table('contacts')->insert($rows);
        }
    }
}

