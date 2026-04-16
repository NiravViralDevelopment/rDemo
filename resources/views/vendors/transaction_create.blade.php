@extends('layouts.admin.app')

@section('content')
    <style>
        .vendor-transaction-page .card {
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            box-shadow: 0 6px 18px rgba(15, 23, 42, 0.05);
        }

        .vendor-transaction-page .card-title {
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 2px;
        }

        .vendor-transaction-page .form-subtitle {
            margin: 0 0 16px;
            color: #64748b;
            font-size: 13px;
        }

        .vendor-transaction-page .balance-box {
            border: 1px solid #dbeafe;
            border-radius: 12px;
            background: linear-gradient(135deg, #eff6ff 0%, #f8fafc 100%);
            padding: 12px 14px;
            margin-bottom: 16px;
        }

        .vendor-transaction-page .balance-row {
            margin: 0;
            color: #1e293b;
            font-size: 14px;
        }

        .vendor-transaction-page .balance-row + .balance-row {
            margin-top: 4px;
        }

        .vendor-transaction-page .form-label {
            font-size: 13px;
            font-weight: 600;
            color: #334155;
            margin-bottom: 6px;
        }

        .vendor-transaction-page .required-star {
            color: #dc2626;
            margin-left: 3px;
        }

        .vendor-transaction-page .form-control {
            border: 1px solid #cbd5e1;
            border-radius: 10px;
            min-height: 42px;
            padding: 8px 12px;
            color: #0f172a;
        }

        .vendor-transaction-page .form-control:focus {
            border-color: #d96432;
            box-shadow: 0 0 0 3px rgba(217, 100, 50, 0.14);
        }

        .vendor-transaction-page textarea.form-control {
            min-height: 92px;
            resize: vertical;
        }

        .vendor-transaction-page .form-actions {
            border-top: 1px solid #e2e8f0;
            margin-top: 16px;
            padding-top: 14px;
            display: flex;
            gap: 8px;
        }

        .vendor-transaction-page .btn-back {
            border-radius: 8px;
            padding: 8px 16px;
        }
    </style>

    <section class="section vendor-transaction-page">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body p-4 p-lg-4">
                        <h5 class="card-title">Vendor Credit / Debit Entry</h5>
                        <p class="form-subtitle">Record incoming credits and debits for this vendor ledger.</p>

                        <div class="balance-box">
                            <p class="balance-row"><strong>Vendor:</strong> {{ $vendor->vendor_name }}</p>
                            <p class="balance-row"><strong>Current Balance:</strong> {{ number_format((float) $balance, 2) }}</p>
                        </div>

                        <form method="POST" action="{{ route('vendors.transactions.store', $vendor->id) }}">
                            @csrf

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Type<span class="required-star">*</span></label>
                                    <select name="type" class="form-control" required>
                                        <option value="credit" {{ old('type') === 'credit' ? 'selected' : '' }}>Credit</option>
                                        <option value="debit" {{ old('type') === 'debit' ? 'selected' : '' }}>Debit</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Amount<span class="required-star">*</span></label>
                                    <input type="number" step="0.01" min="0.01" name="amount" class="form-control" value="{{ old('amount') }}" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Date</label>
                                <input type="date" name="transaction_date" class="form-control" value="{{ old('transaction_date', now()->toDateString()) }}">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Note</label>
                                <textarea name="note" class="form-control">{{ old('note') }}</textarea>
                            </div>

                            <div class="form-actions">
                                <button type="submit" class="btn btn-sm comnBtn">Save Entry</button>
                                <a href="{{ route('vendors.index') }}" class="btn btn-sm btn-secondary btn-back">Back</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

