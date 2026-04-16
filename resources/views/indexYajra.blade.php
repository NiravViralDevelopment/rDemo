@extends('layouts.admin.app')

@section('content')

@if(session('success'))
    <div class="alert alert-success">
        {!! session('success') !!}
    </div>
@endif

@if($errors->has('import'))
    <div class="alert alert-danger">
        {!! $errors->first('import') !!}
    </div>
@endif 

<form action="{{ route('pdf-selected-ids') }}" method="POST"> 
    @csrf
<section class="section">    
    <div class="row">
        <div class="col-lg-12">
            <div class="card downloadSec">
                <div class="card-body"> 
                    <div class="selectSet">
                        @if(Auth::user()->getRoleNames()[0] == 'OBS Warehouse team')
                                
                            
                                <div class="flxSlct">
                                    <select name="assign_driver" id="assign_driver" class="form-control">
                                        <option value="">Select Driver</option>
                                        @foreach ($drivers as $driver)
                                            <option value="{{ $driver->id }}">{{ $driver->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <a class="selectedIDdispatced SuBtn" id="selectedIDdispatced">Assign Driver</a>
                            
                        @endif 
                    </div> 

                   <div class="scrollOvr scroll-container">
                    <table id="datatable" class="table">
                        <thead class="mobileHide">
                            <tr>
                                @if($userRoles[0] == 'Dispatch Team' || $userRoles[0] == 'OBS Warehouse team') 
                                    <th><img src="{{ asset('all_image/pdffile.png') }}" title="Multiple Blank POD Download" alt="Hand Icon" style="width: 28px; height: 28px;"></th>
                                @endif
                                @if($userRoles[0] == 'OBS Warehouse team') 
                                    <th><img src="{{ asset('all_image/dispatched.png') }}" title="Assign Driver" alt="Hand Icon" style="width: 28px; height: 28px;"></th>
                                @endif
                                <th><img src="{{ asset('all_image/down.png') }}" title="Download Signed POD Zip" alt="Hand Icon" style="width: 28px; height: 28px; cursor: pointer;" id="toggle_image"></th>
                                <th class='d-none'>Id</th>
                                <th>VIN Record ID</th>
                                <th>Name</th>
                                <th>Mobile</th>
                                <th>Email</th>
                                <th>Country</th>
                                <th>State</th>
                                <th>Address</th>
                                @if($userRoles[0] != 'Associate')
                                    <th>Location</th>
                                @endif
                                <!-- <th>Driver Name</th> -->
                                <th>Status</th>
                                <!-- <th>Delivery Note</th> -->
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Data will be populated by DataTables -->
                        </tbody>
                    </table>
                   </div>
                </div>
                <div class="paddRts">
                    @if($userRoles[0] == 'Dispatch Team' || $userRoles[0] == 'OBS Warehouse team')    
                        <button class="btn btn-info downloadButton">Multiple Blank POD Download</button>
                    @endif
                    <button class="btn btn-info downloadButton">Download Signed POD Zip</button>
                </div>
            </div>
        </div>
    </div> 
</section>
</form>

<!-- Modal for viewing order details -->
<div class="modal fade" id="viewModal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewModalLabel">Order Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modalBody">
                <!-- Order details will be loaded here dynamically -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<script>
$(document).ready(function() {


    $('#selectedIDdispatced').click(function() {
            let selectedIds = [];

            let selectedDriver = document.getElementById('assign_driver').value;
            console.log(selectedDriver); // This will print the selected value
            
            // Collect all checked checkboxes with name="selectedId[]"
            document.querySelectorAll('input[name="selectedId[]"]:checked').forEach(function (checkbox) {
                selectedIds.push(checkbox.value);
            });

            if (selectedIds.length === 0) {
                toastr.error('Please Select Orders That Are  Ready for Dispatch .');
                return;
            }
            if (!selectedDriver) {
                toastr.error('Please select a driver');
                return;
            }

            let confirmation = confirm(`Are you sure you want to change the status of ${selectedIds.length} selected Orders?`);

            if (confirmation) {
                $.ajax({
                    url: '/update-selected-ids',
                    method: 'POST',
                    data: {
                        orderId: selectedIds,
                        selectedDriver  : selectedDriver,
                        _token: $('meta[name="csrf-token"]').attr('content') // CSRF token
                    },
                    success: function (response) {
                        console.log(response);
                        if (response.success) {
                            toastr.success(response.message);
                            setTimeout(function () {
                                location.reload();
                            }, 2000);
                        } else {
                            toastr.error('Something went wrong!');
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error('Error:', error);
                        toastr.error('Something went wrong!');
                    }
                });
            } else {
                toastr.error('You have canceled the status update action.');
            }
        });

    // Initialize an array to store the selected checkbox IDs globally (across pages)
    var selectedCheckboxes = [];

    // Initialize the DataTable
    var table = $('#datatable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('indexYajra') }}",
        stateSave: true, // Keeps the table's state (pagination, search, etc.) between page reloads
        columns: [
            @if($userRoles[0] == 'Dispatch Team' || $userRoles[0] == 'OBS Warehouse team')
            {
                data: 'id',
                name: 'id',
                orderable: false,
                searchable: false,
                render: function (data, type, row) {
                    return '<input type="checkbox" class="row-checkbox" name="selectedIdPdf[]" value="' + data + '">D-W';
                }
            },
            @endif
            
            @if($userRoles[0] == 'OBS Warehouse team') 
            {
                data: 'id',
                name: 'id',
                orderable: false,
                searchable: false,
                render: function (data, type, row) {
                    return '<input type="checkbox" class="row-checkbox" name="selectedId[]" id="selectedId" value="' + data + '">W';
                }
            },
            @endif
            {
                    data: 'id',
                    name: 'id',
                    orderable: false,
                    searchable: false,
                    render: function (data, type, row) {
                        return '<input type="checkbox" class="row-checkbox" name="selected_pdf_zip[]" id="selected_pdf_zip" value="' + data + '">W';
                    }
            },
            
            { data: 'vin_record_number', name: 'vin_record_number' },
            { data: 'client_name', name: 'client_name' },
            { data: 'mobile_number', name: 'mobile_number' },
            { data: 'email', name: 'email' },
            { data: 'country_name', name: 'country_name' },
            { data: 'cities.city_name', name: 'cities.city_name' },
            { data: 'address', name: 'address' },
            { data: 'deliverynote', name: 'deliverynote', orderable: false, searchable: false },

            
            @if($userRoles[0] != 'Associate')
                { data: 'location', name: 'location' },
            @endif
            { data: 'status', name: 'status' },
            { data: 'action', name: 'action', orderable: false, searchable: false }, 

        ]
    });

      // Handle master checkbox
    $('#select-all').on('click', function () {
        $('.row-checkbox').prop('checked', this.checked);
    });

    // Collect selected rows on action
    $('#action-button').on('click', function () {
        var selectedIds = [];
        $('.row-checkbox:checked').each(function () {
            selectedIds.push($(this).val());
        });

        console.log(selectedIds); // Perform your action here
    });





        // Handle modal data loading
        $(document).on('click', '.view-details', function (e) {
            e.preventDefault(); // Prevent the default action (e.g., page reload)
            
            var orderId = $(this).data('id');
            $('#modalBody').html('<p>Loading...</p>'); // Reset modal content
            
            $.ajax({
                url: "{{ url('order/details') }}/" + orderId,
                type: "GET",
                success: function (data) {
                    $('#modalBody').html(data); // Load order details into modal
                    $('#viewModal').modal('show'); // Show modal
                },
                error: function () {
                    $('#modalBody').html('<p>Error loading order details.</p>');
                }
            });
        });
    });
</script>


@endsection
