@extends('layouts.admin.app')

@section('content')
    @php
        $statusKey = $displayStatus ?? 'pending';

        $b2b = $fileByType['b2b'] ?? null;
        $b2c = $fileByType['b2c'] ?? null;
        $stock = $fileByType['STOCK_TRANSFER'] ?? null;
        $canUploadMissing = empty($b2b) || empty($b2c) || empty($stock);

        $uploaded = $uploadedAt ? \Carbon\Carbon::parse($uploadedAt) : null;
        $updated = $updatedAt ? \Carbon\Carbon::parse($updatedAt) : null;
    @endphp
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card cardPdd amazon-raw-import">
                    <div class="card-body topText">
                        <div class="col-lg-12 margin-tb">
                            <div class="flexBdy flex-wrap align-items-center gap-2">
                                <div class="pull-left flex-grow-1">
                                    <h5 class="card-title mb-0">Amazon raw data import</h5>
                                </div>
                                <div class="pull-right d-flex flex-wrap align-items-center gap-2">
                                    @if(in_array($statusKey, ['processed', 'in_progress'], true))
                                        <form method="POST" action="{{ route('raw-data-import.period-apply-rules') }}" class="d-inline m-0">
                                            @csrf
                                            <input type="hidden" name="month" value="{{ (int) $month }}">
                                            <input type="hidden" name="year" value="{{ (int) $year }}">
                                            <input type="hidden" name="source_type" value="{{ $sourceType ?? '' }}">
                                            <input type="hidden" name="amazon_b2b_file_type" value="b2b">
                                            <input type="hidden" name="amazon_b2c_file_type" value="b2c">
                                            <input type="hidden" name="amazon_stock_transfer_file_type" value="STOCK_TRANSFER">
                                            <button type="submit" class="btn btn-sm btn-outline-warning" title="Apply rules">
                                                <i class="bi bi-funnel-fill me-1"></i> Apply rules
                                            </button>
                                        </form>
                                    @endif
                                    @if(!empty($showPeriodProcessButton))
                                        <form method="POST" action="{{ route('raw-data-import.period-process') }}" class="d-inline m-0">
                                            @csrf
                                            <input type="hidden" name="month" value="{{ (int) $month }}">
                                            <input type="hidden" name="year" value="{{ (int) $year }}">
                                            @if(!empty($sourceType))
                                                <input type="hidden" name="source_type" value="{{ $sourceType }}">
                                            @endif
                                            <button type="submit" class="btn btn-sm"
                                                style="background-color: #0f6f39; color: #fff; border: 1px solid #0f6f39;">
                                                <i class="bi bi-gear-fill me-1"></i> Processing
                                            </button>
                                        </form>
                                    @endif
                                    <a class="btn btn-sm mb-0 comnBtn whtTxt borderBtn" href="{{ route('raw-data-import') }}">
                                        <i class="fa fa-arrow-left"></i> Back
                                    </a>
                                </div>
                            </div>
                        </div>

                        @if (session('message'))
                            <div class="alert alert-success frmGrpMt mb-3" role="alert">{{ session('message') }}</div>
                        @endif
                        @if (session('warning'))
                            <div class="alert alert-warning frmGrpMt mb-3" role="alert">{{ session('warning') }}</div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger frmGrpMt mb-3" role="alert">{{ session('error') }}</div>
                        @endif

                        <div class="frmGrp amazon-import-frm">
                            <div class="row g-3 align-items-center mb-3">
                                <label class="col-12 col-md-4 col-lg-3 col-form-label strng mb-0">Month &amp; year</label>
                                <div class="col-12 col-md-8 col-lg-9">
                                    <div class="form-control bg-light mb-0 py-2" style="cursor: default;">
                                        {{ $periodLabel }}@if(!empty($sourceType)) — {{ $sourceType }} @endif
                                    </div>
                                </div>
                            </div>

                            <form id="amazonPeriodImportForm" method="POST" action="{{ route('raw-data-import.import') }}" enctype="multipart/form-data" class="amazon-upload-stack mt-2">
                                @csrf
                                <input type="hidden" name="month" value="{{ (int) $month }}">
                                <input type="hidden" name="year" value="{{ (int) $year }}">
                                <input type="hidden" name="source_type" value="{{ $sourceType ?: 'Amazon' }}">

                                    <div class="row g-2 align-items-center">
                                        <label class="col-12 col-md-4 col-lg-3 col-form-label strng mb- pt-0">Amazon B2B</label>
                                        <div class="col-12 col-md-8 col-lg-9 mb-3">
                                            @if(!empty($b2b))
                                                <div class="form-control bg-light mb-0 py-2" style="cursor: default;">
                                                    {{ $b2b->filename }}
                                                </div>
                                            @else
                                                <input type="file" name="file_amazon_b2b" id="period_file_amazon_b2b" class="form-control amazon-period-file-input" accept=".xlsx,.csv,text/csv" data-pending-label="Amazon B2B" aria-describedby="period_err_amazon_b2b">
                                                <p id="period_err_amazon_b2b" class="amazon-period-field-msg text-danger small mb-0 mt-2 d-none" role="status"></p>
                                                <!-- <p class="text-muted small mt-2 mb-0">Upload missing B2B file.</p> -->
                                            @endif
                                        </div>
                                    </div>

                                    <div class="row g-2 align-items-center">
                                        <label class="col-12 col-md-4 col-lg-3 col-form-label strng mb-3 pt-0">Amazon B2C</label>
                                        <div class="col-12 col-md-8 col-lg-9 mb-3">
                                            @if(!empty($b2c))
                                                <div class="form-control bg-light mb-0 py-2" style="cursor: default;">
                                                    {{ $b2c->filename }}
                                                </div>
                                            @else
                                                <input type="file" name="file_amazon_b2c" id="period_file_amazon_b2c" class="form-control amazon-period-file-input" accept=".xlsx,.csv,text/csv" data-pending-label="Amazon B2C" aria-describedby="period_err_amazon_b2c">
                                                <p id="period_err_amazon_b2c" class="amazon-period-field-msg text-danger small mb-0 mt-2 d-none" role="status"></p>
                                            @endif
                                        </div>
                                    </div>
                                

                                    <div class="row g-2 align-items-start align-items-md-center">
                                        <label class="col-12 col-md-4 col-lg-3 col-form-label strng mb-3 pt-md-2">
                                            <span class="text-nowrap">Amazon STOCK TRANSFER</span>
                                            <span class="d-block small fw-normal text-muted mt-1 d-none">STOCK_TRANSFER</span>
                                        </label>
                                        <div class="col-12 col-md-8 col-lg-9 mb-3">
                                            @if(!empty($stock))
                                                <div class="form-control bg-light mb-0 py-2" style="cursor: default;">
                                                    {{ $stock->filename }}
                                                </div>
                                            @else
                                                <input type="file" name="file_amazon_stock_transfer" id="period_file_amazon_stock_transfer" class="form-control amazon-period-file-input" accept=".xlsx,.csv,text/csv" data-pending-label="Amazon STOCK_TRANSFER" aria-describedby="period_err_amazon_stock">
                                                <p id="period_err_amazon_stock" class="amazon-period-field-msg text-danger small mb-0 mt-2 d-none" role="status"></p>
                                            @endif
                                        </div>
                                    </div>
                                

                                @if($canUploadMissing)
                                    <div class="row g-3 mt-1 mb-4 pb-2">
                                        <div class="col-12 col-md-8 col-lg-9 offset-md-4 offset-lg-3">
                                            <button type="submit" id="rawImportSubmitBtn" class="btn btn-sm"
                                                style="background-color: #0f6f39; color: #fff; border: 1px solid #0f6f39;">
                                                Import Now
                                            </button>
                                        </div>
                                    </div>
                                @endif
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>

    <style>
        .amazon-raw-import.cardPdd::before {
            display: none;
        }
        .amazon-raw-import .frmGrp.amazon-import-frm {
            padding-top: 0.75rem;
        }
        .amazon-raw-import .frmGrp .form-control {
            margin-top: 0;
        }
    </style>
@endsection

@push('scripts')
    <script>
        (function () {
            var form = document.getElementById('amazonPeriodImportForm');
            var FIELD_MSG = 'This field is required.';

            function clearFieldValidation() {
                if (!form) {
                    return;
                }
                form.querySelectorAll('.amazon-period-file-input').forEach(function (inp) {
                    inp.setAttribute('aria-invalid', 'false');
                });
                form.querySelectorAll('.amazon-period-field-msg').forEach(function (el) {
                    el.textContent = '';
                    el.classList.add('d-none');
                });
            }

            /** True if at least one pending-slot file input has a file chosen (matches main Amazon import: at least one file). */
            function hasAnyPendingFileSelected() {
                if (!form) {
                    return false;
                }
                var any = false;
                form.querySelectorAll('.amazon-period-file-input').forEach(function (inp) {
                    if (inp.files && inp.files.length) {
                        any = true;
                    }
                });
                return any;
            }

            function showPerFieldRequired() {
                if (!form) {
                    return;
                }
                form.querySelectorAll('.amazon-period-file-input').forEach(function (inp) {
                    var empty = !inp.files || !inp.files.length;
                    var msgEl = inp.parentElement
                        ? inp.parentElement.querySelector('.amazon-period-field-msg')
                        : null;
                    if (empty) {
                        inp.setAttribute('aria-invalid', 'true');
                        if (msgEl) {
                            msgEl.textContent = FIELD_MSG;
                            msgEl.classList.remove('d-none');
                        }
                    } else {
                        inp.setAttribute('aria-invalid', 'false');
                        if (msgEl) {
                            msgEl.textContent = '';
                            msgEl.classList.add('d-none');
                        }
                    }
                });
            }

            function focusFirstEmptyPendingInput() {
                if (!form) {
                    return;
                }
                var inputs = form.querySelectorAll('.amazon-period-file-input');
                for (var i = 0; i < inputs.length; i++) {
                    var inp = inputs[i];
                    if (!inp.files || !inp.files.length) {
                        try {
                            inp.focus();
                        } catch (err) {
                            /* ignore */
                        }
                        return;
                    }
                }
            }

            document.addEventListener('DOMContentLoaded', function () {
                if (!form) {
                    return;
                }

                form.querySelectorAll('.amazon-period-file-input').forEach(function (inp) {
                    inp.addEventListener('change', function () {
                        if (hasAnyPendingFileSelected()) {
                            clearFieldValidation();
                        }
                    });
                });

                form.addEventListener('submit', function (e) {
                    var inputs = form.querySelectorAll('.amazon-period-file-input');
                    if (!inputs.length) {
                        return;
                    }
                    if (hasAnyPendingFileSelected()) {
                        clearFieldValidation();
                        return;
                    }
                    e.preventDefault();
                    showPerFieldRequired();
                    focusFirstEmptyPendingInput();
                });
            });
        })();
    </script>
@endpush
