@extends('layouts.admin.app')

@section('content')
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                {{-- amazon-raw-import: disables global .cardPdd::before line that was cutting through text --}}
                <div class="card cardPdd amazon-raw-import">
                    <div class="card-body topText">
                        <div class="col-lg-12 margin-tb">
                            <div class="flexBdy">
                                <div class="pull-left">
                                    <h5 class="card-title">Amazon raw data import (XLSX / CSV)</h5>
                                </div>
                                <div class="pull-right">
                                    <a class="btn btn-sm mb-2 comnBtn whtTxt borderBtn" href="{{ route('raw-data-import') }}">
                                        <i class="fa fa-arrow-left"></i> Back
                                    </a>
                                </div>
                            </div>
                        </div>

                        @if (count($errors) > 0)
                            <div class="alert alert-danger frmGrpMt">
                                <strong>Whoops!</strong> There were some problems with your input.<br><br>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @php
                            $defaultPeriod = \Carbon\Carbon::now()->startOfMonth()->subMonth();
                            $oldMonth = (int) old('month', $defaultPeriod->month);
                            $oldYear = (int) old('year', $defaultPeriod->year);
                            $periodLabel = \Carbon\Carbon::createFromDate($oldYear, $oldMonth, 1)->format('F Y');
                        @endphp

                        <!-- <p class="text-muted small mb-3 amazon-import-intro">
                            Each file is saved with <strong>Source Type: Amazon</strong> and the matching <strong>File Type</strong> (B2B, B2C, or STOCK_TRANSFER). Upload one or more files in a single submit.
                        </p> -->

                        <div class="frmGrp amazon-import-frm">
                            <form action="{{ route('raw-data-import.import') }}" method="POST" enctype="multipart/form-data" id="rawImportForm"
                                data-period-label="{{ e($periodLabel) }}"
                                data-check-url="{{ route('raw-data-import.check-duplicate') }}">
                                @csrf
                                <input type="hidden" name="source_type" value="Amazon">

                                <div id="amazonClientValidationMsg" class="alert alert-danger d-none mb-3" role="alert" aria-live="polite"></div>

                                <div class="row g-3 align-items-center mb-3">
                                    <label class="col-12 col-md-4 col-lg-3 col-form-label strng mb-0" for="amazon-period-display">Month &amp; year <span class="text-danger">*</span></label>
                                    <div class="col-12 col-md-8 col-lg-9">
                                        <div id="amazon-period-display" class="form-control bg-light mb-0 py-2" style="cursor: default;" aria-readonly="true" title="Previous calendar month (read only)">
                                            {{ $periodLabel }}
                                        </div>
                                        <input type="hidden" name="month" id="month" value="{{ $oldMonth }}">
                                        <input type="hidden" name="year" id="year" value="{{ $oldYear }}">
                                    </div>
                                </div>

                                <div class="amazon-upload-stack">
                                    <div class="amazon-upload-row border rounded-3 p-3 mb-3 bg-white">
                                        <div class="row g-2 align-items-center">
                                            <label class="col-12 col-md-4 col-lg-3 col-form-label strng mb-0 pt-0" for="file_amazon_b2b">Amazon B2B</label>
                                            <div class="col-12 col-md-8 col-lg-9">
                                                <input type="file" name="file_amazon_b2b" id="file_amazon_b2b" class="form-control amazon-file-input" accept=".xlsx,.csv,text/csv" data-amazon-ft="b2b" aria-describedby="dupWarnAmazonB2b">
                                                <p class="amazon-import-field-msg text-danger small mb-0 mt-2 d-none" role="status"></p>
                                                <p id="dupWarnAmazonB2b" class="text-danger small mb-0 d-none mt-2" role="status"></p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="amazon-upload-row border rounded-3 p-3 mb-3 bg-white">
                                        <div class="row g-2 align-items-center">
                                            <label class="col-12 col-md-4 col-lg-3 col-form-label strng mb-0 pt-0" for="file_amazon_b2c">Amazon B2C</label>
                                            <div class="col-12 col-md-8 col-lg-9">
                                                <input type="file" name="file_amazon_b2c" id="file_amazon_b2c" class="form-control amazon-file-input" accept=".xlsx,.csv,text/csv" data-amazon-ft="b2c" aria-describedby="dupWarnAmazonB2c">
                                                <p class="amazon-import-field-msg text-danger small mb-0 mt-2 d-none" role="status"></p>
                                                <p id="dupWarnAmazonB2c" class="text-danger small mb-0 d-none mt-2" role="status"></p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="amazon-upload-row border rounded-3 p-3 mb-3 bg-white">
                                        <div class="row g-2 align-items-start align-items-md-center">
                                            <label class="col-12 col-md-4 col-lg-3 col-form-label strng mb-0 pt-md-2" for="file_amazon_stock_transfer">
                                                <span class="text-nowrap">Amazon STOCK TRANSFER</span>
                                                <span class="d-block small fw-normal text-muted mt-1 d-none">STOCK_TRANSFER</span>
                                            </label>
                                            <div class="col-12 col-md-8 col-lg-9">
                                                <input type="file" name="file_amazon_stock_transfer" id="file_amazon_stock_transfer" class="form-control amazon-file-input" accept=".xlsx,.csv,text/csv" data-amazon-ft="STOCK_TRANSFER" aria-describedby="dupWarnAmazonStock">
                                                <p class="amazon-import-field-msg text-danger small mb-0 mt-2 d-none" role="status"></p>
                                                <p id="dupWarnAmazonStock" class="text-danger small mb-0 d-none mt-2" role="status"></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row g-3 mt-1 mb-4 pb-2">
                                    <div class="col-12 col-md-8 col-lg-9 offset-md-4 offset-lg-3">
                                        <button type="submit" id="rawImportSubmitBtn" class="btn btn-sm"
                                            style="background-color: #0f6f39; color: #fff; border: 1px solid #0f6f39;">
                                            Import Now
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>

    <style>
        /* Remove decorative horizontal rule from .cardPdd that crossed the intro text */
        .amazon-raw-import.cardPdd::before {
            display: none;
        }
        .amazon-raw-import .frmGrp.amazon-import-frm {
            padding-top: 0.75rem;
        }
        .amazon-raw-import .frmGrp .form-control {
            margin-top: 0;
        }
        .amazon-import-intro {
            line-height: 1.5;
            padding-right: 0.5rem;
        }
    </style>
@endsection

@push('scripts')
    <script>
        (function () {
            const monthEl = document.getElementById('month');
            const yearEl = document.getElementById('year');
            const formEl = document.getElementById('rawImportForm');
            const clientValidationMsg = document.getElementById('amazonClientValidationMsg');
            const FIELD_REQUIRED_MSG = 'This field is required.';
            const amazonWarnEls = {
                b2b: document.getElementById('dupWarnAmazonB2b'),
                b2c: document.getElementById('dupWarnAmazonB2c'),
                STOCK_TRANSFER: document.getElementById('dupWarnAmazonStock')
            };
            const periodLabel = (formEl && formEl.dataset.periodLabel) ? String(formEl.dataset.periodLabel).trim() : '';
            const checkUrl = formEl && formEl.dataset.checkUrl ? String(formEl.dataset.checkUrl).trim() : '';

            var amazonDup = { b2b: false, b2c: false, STOCK_TRANSFER: false };
            var checkTimer = null;
            var amazonAbort = null;

            function clearImportFieldRequiredMsgs() {
                if (!formEl) {
                    return;
                }
                formEl.querySelectorAll('.amazon-import-field-msg').forEach(function (el) {
                    el.textContent = '';
                    el.classList.add('d-none');
                });
                formEl.querySelectorAll('.amazon-file-input').forEach(function (inp) {
                    inp.setAttribute('aria-invalid', 'false');
                });
            }

            function clearClientFileValidation() {
                clearImportFieldRequiredMsgs();
                if (clientValidationMsg) {
                    clientValidationMsg.textContent = '';
                    clientValidationMsg.classList.add('d-none');
                    clientValidationMsg.classList.remove('alert-danger', 'alert-warning');
                }
            }

            function showImportPerFieldRequired() {
                if (!formEl) {
                    return;
                }
                formEl.querySelectorAll('.amazon-file-input').forEach(function (inp) {
                    var empty = !inp.files || !inp.files.length;
                    var ft = inp.getAttribute('data-amazon-ft');
                    var dupForSlot = ft && amazonDup[ft];
                    var msgEl = inp.parentElement
                        ? inp.parentElement.querySelector('.amazon-import-field-msg')
                        : null;
                    if (empty && dupForSlot) {
                        inp.setAttribute('aria-invalid', 'false');
                        if (msgEl) {
                            msgEl.textContent = '';
                            msgEl.classList.add('d-none');
                        }
                    } else if (empty) {
                        inp.setAttribute('aria-invalid', 'true');
                        if (msgEl) {
                            msgEl.textContent = FIELD_REQUIRED_MSG;
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

            function focusFirstEmptyAmazonInput() {
                var inputs = document.querySelectorAll('.amazon-file-input');
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

            function showClientFileValidation(message, variant) {
                if (!clientValidationMsg) {
                    return;
                }
                variant = variant || 'danger';
                clientValidationMsg.classList.remove('alert-danger', 'alert-warning');
                clientValidationMsg.classList.add(variant === 'danger' ? 'alert-danger' : 'alert-warning');
                clientValidationMsg.textContent = message;
                clientValidationMsg.classList.remove('d-none');
                clientValidationMsg.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            }

            function hasAnyAmazonFile() {
                var inputs = document.querySelectorAll('.amazon-file-input');
                var any = false;
                inputs.forEach(function (inp) {
                    if (inp.files && inp.files.length) {
                        any = true;
                    }
                });
                return any;
            }

            function applyAmazonDuplicateUI() {
                var labels = { b2b: 'B2B', b2c: 'B2C', STOCK_TRANSFER: 'STOCK_TRANSFER' };
                ['b2b', 'b2c', 'STOCK_TRANSFER'].forEach(function (k) {
                    var el = amazonWarnEls[k];
                    if (!el) {
                        return;
                    }
                    if (amazonDup[k]) {
                        var pl = periodLabel || 'the selected month';
                        el.textContent = 'Import already exists for ' + pl + ' for Amazon ' + labels[k] + '. You can still upload the other file types.';
                        el.hidden = false;
                        el.classList.remove('d-none');
                    } else {
                        el.hidden = true;
                        el.classList.add('d-none');
                        el.textContent = '';
                    }
                });
            }

            function clearAmazonWarnings() {
                amazonDup = { b2b: false, b2c: false, STOCK_TRANSFER: false };
                Object.keys(amazonWarnEls).forEach(function (k) {
                    var el = amazonWarnEls[k];
                    if (el) {
                        el.hidden = true;
                        el.classList.add('d-none');
                        el.textContent = '';
                    }
                });
            }

            function runDuplicateCheck() {
                if (!checkUrl || !monthEl || !yearEl || !monthEl.value || !yearEl.value) {
                    clearAmazonWarnings();
                    return;
                }
                if (amazonAbort) {
                    amazonAbort.abort();
                }
                amazonAbort = new AbortController();
                var signal = amazonAbort.signal;
                var types = ['b2b', 'b2c', 'STOCK_TRANSFER'];
                var paramsBase = {
                    month: monthEl.value,
                    year: yearEl.value,
                    source_type: 'Amazon'
                };
                Promise.all(types.map(function (ft) {
                    var params = new URLSearchParams(Object.assign({}, paramsBase, { file_type: ft }));
                    return fetch(checkUrl + '?' + params.toString(), {
                        method: 'GET',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        signal: signal,
                        credentials: 'same-origin'
                    }).then(function (r) {
                        if (!r.ok) {
                            throw new Error('check failed');
                        }
                        return r.json();
                    }).then(function (data) {
                        return { ft: ft, exists: !!(data && data.exists) };
                    });
                })).then(function (results) {
                    amazonDup.b2b = false;
                    amazonDup.b2c = false;
                    amazonDup.STOCK_TRANSFER = false;
                    results.forEach(function (row) {
                        if (row.ft === 'b2b') {
                            amazonDup.b2b = row.exists;
                        } else if (row.ft === 'b2c') {
                            amazonDup.b2c = row.exists;
                        } else if (row.ft === 'STOCK_TRANSFER') {
                            amazonDup.STOCK_TRANSFER = row.exists;
                        }
                    });
                    applyAmazonDuplicateUI();
                }).catch(function (err) {
                    if (err.name === 'AbortError') {
                        return;
                    }
                    clearAmazonWarnings();
                });
            }

            function scheduleDuplicateCheck() {
                if (checkTimer) {
                    clearTimeout(checkTimer);
                }
                checkTimer = setTimeout(runDuplicateCheck, 250);
            }

            $(document).ready(function () {
                if (!formEl || !window.jQuery) {
                    return;
                }
                scheduleDuplicateCheck();

                $('.amazon-file-input').on('change', function () {
                    if (hasAnyAmazonFile()) {
                        clearClientFileValidation();
                    }
                    var ft = this.getAttribute('data-amazon-ft');
                    if (ft === 'b2b' && this.files && this.files.length && amazonDup.b2b) {
                        this.value = '';
                    }
                    if (ft === 'b2c' && this.files && this.files.length && amazonDup.b2c) {
                        this.value = '';
                    }
                    if (ft === 'STOCK_TRANSFER' && this.files && this.files.length && amazonDup.STOCK_TRANSFER) {
                        this.value = '';
                    }
                });

                if (formEl) {
                    formEl.addEventListener('submit', function (e) {
                        clearClientFileValidation();
                        var inputs = document.querySelectorAll('.amazon-file-input');
                        var any = false;
                        var blocked = false;
                        inputs.forEach(function (inp) {
                            if (inp.files && inp.files.length) {
                                any = true;
                                var ft = inp.getAttribute('data-amazon-ft');
                                if (ft === 'b2b' && amazonDup.b2b) {
                                    blocked = true;
                                }
                                if (ft === 'b2c' && amazonDup.b2c) {
                                    blocked = true;
                                }
                                if (ft === 'STOCK_TRANSFER' && amazonDup.STOCK_TRANSFER) {
                                    blocked = true;
                                }
                            }
                        });
                        if (!any) {
                            e.preventDefault();
                            showImportPerFieldRequired();
                            focusFirstEmptyAmazonInput();
                            return;
                        }
                        if (blocked) {
                            e.preventDefault();
                            showClientFileValidation('One or more selected files already have an import for this month. Remove those files or choose a different period.', 'danger');
                        }
                    });
                }
            });
        })();
    </script>
@endpush
