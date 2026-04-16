<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class BankController extends Controller
{
    public function index()
    {
        return view('banks.index');
    }

    public function data()
    {
        $query = Bank::query()
            ->withSum('credits as total_credit', 'amount')
            ->withSum('debits as total_debit', 'amount')
            ->latest('id');

        return DataTables::eloquent($query)
            ->addColumn('opening_balance_display', fn (Bank $bank) => number_format((float) $bank->opening_balance, 2))
            ->addColumn('total_credit_display', fn (Bank $bank) => number_format((float) ($bank->total_credit ?? 0), 2))
            ->addColumn('total_debit_display', fn (Bank $bank) => number_format((float) ($bank->total_debit ?? 0), 2))
            ->addColumn('current_balance_display', function (Bank $bank) {
                $balance = (float) $bank->opening_balance + (float) ($bank->total_credit ?? 0) - (float) ($bank->total_debit ?? 0);
                return number_format($balance, 2);
            })
            ->addColumn('action', function (Bank $bank) {
                $transactionsUrl = route('banks.transactions.index', $bank->id);
                $editUrl = route('banks.edit', $bank->id);
                $deleteUrl = route('banks.destroy', $bank->id);
                $token = csrf_token();

                return '
                    <a href="' . $transactionsUrl . '" class="btn btn-sm btn-warning text-white" title="Manage Transactions">
                        <i class="bi bi-cash-stack"></i>
                    </a>
                    <a href="' . $editUrl . '" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-pencil-square"></i>
                    </a>
                    <form method="POST" action="' . $deleteUrl . '" class="d-inline">
                        <input type="hidden" name="_token" value="' . $token . '">
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm(\'Delete this bank record?\')">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                ';
            })
            ->rawColumns(['action'])
            ->toJson();
    }

    public function create()
    {
        return view('banks.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'bank_name' => ['required', 'string', 'max:150'],
            'ifsc_code' => ['required', 'string', 'max:30'],
            'branch_name' => ['nullable', 'string', 'max:150'],
            'account_number' => ['required', 'string', 'max:50'],
            'account_holder_name' => ['required', 'string', 'max:150'],
            'opening_balance' => ['nullable', 'numeric'],
            'description' => ['nullable', 'string'],
        ]);
        $validated['opening_balance'] = $validated['opening_balance'] ?? 0;

        Bank::query()->create($validated);

        return redirect()->route('banks.index')->with('message', 'Bank created successfully.');
    }

    public function edit(int $id)
    {
        $bank = Bank::query()->findOrFail($id);
        return view('banks.edit', compact('bank'));
    }

    public function update(Request $request, int $id)
    {
        $bank = Bank::query()->findOrFail($id);
        $validated = $request->validate([
            'bank_name' => ['required', 'string', 'max:150'],
            'ifsc_code' => ['required', 'string', 'max:30'],
            'branch_name' => ['nullable', 'string', 'max:150'],
            'account_number' => ['required', 'string', 'max:50'],
            'account_holder_name' => ['required', 'string', 'max:150'],
            'opening_balance' => ['nullable', 'numeric'],
            'description' => ['nullable', 'string'],
        ]);
        $validated['opening_balance'] = $validated['opening_balance'] ?? 0;

        $bank->update($validated);

        return redirect()->route('banks.index')->with('message', 'Bank updated successfully.');
    }

    public function destroy(int $id)
    {
        $bank = Bank::query()->findOrFail($id);
        $bank->delete();

        return redirect()->route('banks.index')->with('message', 'Bank deleted successfully.');
    }
}
