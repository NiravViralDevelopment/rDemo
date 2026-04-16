@extends('layouts.admin.app')

@section('content')
<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-11">
                            <h5 class="card-title">Raw Data Import</h5>
                        </div>
                        <div class="col-1">
                            @if(!$hasImportForDefaultPeriod)
                                <a href="{{ route('raw-data-import.import-form') }}"
                                   class="btn btn-sm mt-2"
                                   style="background-color: #0f6f39; color: #fff; border-color: #0f6f39;">
                                    <i class="fa fa-plus"></i> Import
                                </a>
                            @endif
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table id="datatable" class="table">
                            <thead class="mobileHide">
                            <tr>
                                <th>No</th>
                                <th>Title</th>
                                <th>Uploaded date &amp; time</th>
                                <th>Updated date &amp; time</th>
                                <!-- <th>Status</th> -->
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($groups as $g)
                                @php
                                    $periodTitle = \Carbon\Carbon::createFromDate((int) $g->year, (int) $g->month, 1)->format('F Y');
                                    if (!empty($g->source_type)) {
                                        $periodTitle    ;
                                    }
                                    $uploaded = $g->uploaded_at ? \Carbon\Carbon::parse($g->uploaded_at) : null;
                                    $updated = $g->updated_at ? \Carbon\Carbon::parse($g->updated_at) : null;
                                    $statusKey = $g->display_status ?? 'pending';
                                    $statusLabels = [
                                        'pending' => 'Pending',
                                        'processed' => 'Processed',
                                        'rule_applied' => 'Rule applied',
                                        'in_progress' => 'In progress',
                                    ];
                                    $statusClasses = [
                                        'pending' => 'badge rounded-pill bg-secondary',
                                        'processed' => 'badge rounded-pill bg-primary text-white',
                                        'rule_applied' => 'badge rounded-pill bg-success',
                                        'in_progress' => 'badge rounded-pill bg-info text-dark',
                                    ];
                                    $statusLabel = $statusLabels[$statusKey] ?? ucfirst(str_replace('_', ' ', $statusKey));
                                    $statusClass = $statusClasses[$statusKey] ?? 'badge rounded-pill bg-secondary';
                                    $periodQuery = ['month' => $g->month, 'year' => $g->year];
                                    if (($g->source_type ?? '') !== '') {
                                        $periodQuery['source_type'] = $g->source_type;
                                    }
                                @endphp
                                <tr class="flexTbl">
                                    <td><span class="mobileShow">No. :</span> {{ $loop->iteration }}</td>
                                    <td><span class="mobileShow">Title :</span> {{ $periodTitle }}</td>
                                    <td><span class="mobileShow">Uploaded :</span> {{ $uploaded ? $uploaded->format('d/m/Y H:i') : '—' }}</td>
                                    <td><span class="mobileShow">Updated :</span> {{ $updated ? $updated->format('d/m/Y H:i') : '—' }}</td>
                            <!-- <td><span class="mobileShow">Status :</span> <span class="{{ $statusClass }}">{{ $statusLabel }}</span></td> -->
                                    <td>
                                        <div class="ThreeBtns">
                                            <a href="{{ route('raw-data-import.period', $periodQuery) }}" class="btn btn-outline-primary btn-sm" title="View">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <form method="POST" action="{{ route('raw-data-import.period-delete') }}" style="display:inline;" onsubmit="return confirm('Delete all imports for this period ({{ $periodTitle }})? This cannot be undone.');">
                                                @csrf
                                                @foreach($periodQuery as $k => $v)
                                                    <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                                                @endforeach
                                                <button type="submit" class="btn btn-outline-danger btn-sm" title="Delete">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    $(document).ready(function() {
        $('#datatable').DataTable({
            dom: 'frtip',
            pageLength: 8,
            language: {
                search: " ",
                searchPlaceholder: "Search"
            }
        });
    });
</script>
@endsection
