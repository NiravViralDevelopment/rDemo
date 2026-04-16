<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class BankTransactionController extends Controller
{
    public function index(int $bankId)
    {
        $bank = Bank::query()
            ->withSum('credits as total_credit', 'amount')
            ->withSum('debits as total_debit', 'amount')
            ->findOrFail($bankId);

        $creditTotal = (float) ($bank->total_credit ?? 0);
        $debitTotal = (float) ($bank->total_debit ?? 0);
        $currentBalance = (float) $bank->opening_balance + $creditTotal - $debitTotal;

        return view('banks.transactions.index', compact('bank', 'creditTotal', 'debitTotal', 'currentBalance'));
    }

    public function data(int $bankId)
    {
        $bank = Bank::query()->findOrFail($bankId);
        $query = $bank->transactions()->latest('transaction_date')->latest('id');

        return DataTables::eloquent($query)
            ->addColumn('amount_display', fn ($tx) => number_format((float) $tx->amount, 2))
            ->addColumn('date_display', fn ($tx) => optional($tx->transaction_date)->format('Y-m-d'))
            ->addColumn('gst_number_display', fn ($tx) => $tx->gst_number ?: '-')
            ->addColumn('gst_percent_display', fn ($tx) => $tx->gst_percent !== null ? number_format((float) $tx->gst_percent, 2) . '%' : '-')
            ->addColumn('type_badge', function ($tx) {
                $badge = $tx->type === 'credit' ? 'bg-success' : 'bg-danger';
                return '<span class="badge ' . $badge . '">' . ucfirst($tx->type) . '</span>';
            })
            ->addColumn('action', function ($tx) use ($bank) {
                $editUrl = route('banks.transactions.edit', [$bank->id, $tx->id]);
                $deleteUrl = route('banks.transactions.destroy', [$bank->id, $tx->id]);
                $token = csrf_token();

                return '
                    <a href="' . $editUrl . '" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-pencil-square"></i>
                    </a>
                    <form method="POST" action="' . $deleteUrl . '" class="d-inline">
                        <input type="hidden" name="_token" value="' . $token . '">
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm(\'Delete this transaction?\')">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                ';
            })
            ->rawColumns(['type_badge', 'action'])
            ->toJson();
    }

    public function create(int $bankId)
    {
        $bank = Bank::query()->findOrFail($bankId);
        return view('banks.transactions.create', compact('bank'));
    }

    public function store(Request $request, int $bankId)
    {
        $bank = Bank::query()->findOrFail($bankId);
        $validated = $request->validate([
            'type' => ['required', 'in:credit,debit'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'transaction_date' => ['required', 'date'],
            'gst_number' => ['nullable', 'string', 'max:30'],
            'gst_percent' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'description' => ['nullable', 'string'],
        ]);

        $bank->transactions()->create($validated);

        return redirect()->route('banks.transactions.index', $bank->id)->with('message', 'Transaction added successfully.');
    }

    public function edit(int $bankId, int $transactionId)
    {
        $bank = Bank::query()->findOrFail($bankId);
        $transaction = $bank->transactions()->findOrFail($transactionId);

        return view('banks.transactions.edit', compact('bank', 'transaction'));
    }

    public function update(Request $request, int $bankId, int $transactionId)
    {
        $bank = Bank::query()->findOrFail($bankId);
        $transaction = $bank->transactions()->findOrFail($transactionId);
        $validated = $request->validate([
            'type' => ['required', 'in:credit,debit'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'transaction_date' => ['required', 'date'],
            'gst_number' => ['nullable', 'string', 'max:30'],
            'gst_percent' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'description' => ['nullable', 'string'],
        ]);

        $transaction->update($validated);

        return redirect()->route('banks.transactions.index', $bank->id)->with('message', 'Transaction updated successfully.');
    }

    public function destroy(int $bankId, int $transactionId)
    {
        $bank = Bank::query()->findOrFail($bankId);
        $transaction = $bank->transactions()->findOrFail($transactionId);
        $transaction->delete();

        return redirect()->route('banks.transactions.index', $bank->id)->with('message', 'Transaction deleted successfully.');
    }
}
