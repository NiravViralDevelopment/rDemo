@extends('layouts.admin.app')
@section('content')

<style>
  /* Modal Design */
  .modal-content {
    border-radius: 8px; 
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
  }

  .modal-body {
    font-size: 1rem;
    line-height: 1.5;
  }

  /* Order Details */
  .order-details p {
    margin-bottom: 0.5rem;
  }

  .order-details strong {
    color: #343a40;
  }

  /* Order Tracking Timeline */
  .timeline {
    list-style-type: none;
    position: relative;
    padding-left: 0;
  }

  .timeline:before {
    content: '';
    background: #28a745;
    position: absolute;
    top: 0;
    left: 24px;
    width: 3px;
    height: 100%;
  }

  .timeline-item {
    position: relative;
    margin: 20px 0;
    padding-left: 60px;
  }

  .timeline-item:before {
    content: '';
    background: #fff;
    border: 3px solid #28a745; 
    position: absolute;
    top: 0;
    left: 15px;
    width: 20px; /* Smaller width */
    height: 20px; /* Smaller height */
    border-radius: 50%;
  }

  .timeline-item .badge {
    font-size: 0.875rem;
  }

  /* POD Image */
  .pod-image {
    border-radius: 4px;
    border: 1px solid #dee2e6;
    transition: transform 0.3s ease;
  }

  .pod-image:hover {
    transform: scale(1.1);
  }

  .modal-footer {
    background-color: #f8f9fa;
    border-top: 1px solid #dee2e6;
  }

  /* Badge Icons */
  .badge-icon {
    margin-right: 8px;
  }

    /* Custom styling for the search box */
    .dataTables_filter {
        text-align: left !important; /* Align search box to the left */
    }

    /* Styling the input box inside the search filter */
    .dataTables_filter input {
        background-color: red;  /* Red background color */
        color: black;           /* Text color */
        border: none;
        padding: 5px;
        border-radius: 4px;
        padding-left: 30px;     /* Space for the magnifier icon */
        background-image: url('https://cdn-icons-png.flaticon.com/512/622/622669.png'); /* Magnifier icon */
        background-size: 20px;
        background-position: 5px center;
        background-repeat: no-repeat;
        float: left;
    }

    /* Optional: Hover effect */
    .dataTables_filter input:focus {
        outline: none;
        background-color: darkred;
    }
</style>
<style>
    /* Align the export button to the right */
    .dt-buttons {
        float: right;  /* Align the buttons to the right */
    }

    /* Custom styles for the export button */
    .btn-export {
        background-color: #007bff;  /* Bootstrap primary blue color */
        color: white;
        border-radius: 4px;
        padding: 5px 10px;
        border: none;
        font-size: 14px;
        float: right;
    }

    .btn-export i {
        margin-right: 5px;  /* Space between icon and text */
    }

    /* Change button color on hover */
    .btn-export:hover {
        background-color: #0056b3;  /* Darker blue */
        color: white;
    }

    /* Ensure the search box aligns to the left */
    .dataTables_filter {
        text-align: left !important;
    }
    #datatable_filter{
        float: left;
    }
    #datatable_wrapper > div.dt-buttons{
        float: right;
    }

    /* Custom styling for the search input box */
  
</style>
<style>
    /* Custom styling for the sort button */
    .btn-sort {
        background-color: #28a745;  /* Bootstrap success green color */
        color: white;
        border-radius: 4px;
        padding: 5px 10px;
        border: none;
        font-size: 14px;
        margin-left: 5px;  /* Add space between export and sort buttons */
    }

    .btn-sort i {
        margin-right: 5px;  /* Space between icon and text */
    }

    /* Change button color on hover */
    .btn-sort:hover {
        background-color: #218838;  /* Darker green */
        color: white;
    }

    /* Existing styles for the export button */
    .btn-export {
        background-color: #007bff;
        color: white;
        border-radius: 4px;
        padding: 5px 10px;
        border: none;
        font-size: 14px;
    }

    .btn-export i {
        margin-right: 5px;
    }

    .btn-export:hover {
        background-color: #0056b3;
        color: white;
    }

    /* Align buttons to the right */
    .dt-buttons {
        float: right;
    }

    /* Custom styling for the search input box */
    .dataTables_filter input {
        background-color: red;
        color: white;
        border: none;
        padding: 5px;
        border-radius: 4px;
        padding-left: 30px;
        background-image: url('https://cdn-icons-png.flaticon.com/512/622/622669.png');
        background-size: 20px;
        background-position: 5px center;
        background-repeat: no-repeat;
    }

    .dataTables_filter {
        text-align: left !important;
    }
</style>

<section class="section">
      <div class="row">
        <div class="col-lg-12">

          <div class="card">
            <div class="card-body">
                <!-- <div class="row">
                    <div class="col-11">
                    <h5 class="card-title">Role Management</h5>
                    </div>
                    <div class="col-1">
                        {{-- @can('role-create')
                            <a class="btn btn-outline-primary btn-sm mt-3" href="{{ route('roles.create') }}">Add</a>
                        @endcan --}}
                    </div>
                </div> -->
            <!-- Table with stripped rows -->
              <table id="datatable" class="table">
                <thead class="mobileHide">
                <tr>
                    <th width="100px">No.</th>
                    <th>Name</th>
                    <th width="280px">Action</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($roles as $key => $role)
                    <tr class="flexTbl">
                        <td><span class="mobileShow">No. :</span> {{ ++$i }}</td>
                        <td><span class="mobileShow">Name :</span> {{ $role->name }}</td>
                        <td>
                           <div class="ThreeBtns">
                            <a  href="{{ route('roles.show',$role->id) }}"><i class="btn btn-outline-success btn-sm bi bi-eye-fill"></i> </a>
                            @can('role-edit')
                                <a  href="{{ route('roles.edit',$role->id) }}"><i class="bi bi-pencil-square btn btn-outline-primary btn-sm"></i></a>
                            @endcan

                            {{-- @can('role-delete')
                            <form method="POST" action="{{ route('roles.destroy', $role->id) }}" style="display:inline">
                                @csrf
                                @method('DELETE')

                                <button type="submit" class="btn btn-danger btn-sm"><i class="fa-solid fa-trash"></i> Delete</button>
                            </form>
                            @endcan --}}
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>

              </table>
<!-- 
              <table id="datatable1" class="table mobileShow">
                
                <tbody>
                @foreach ($roles as $key => $role)
                    <tr class="flexTbl">
                        <td>No : {{ ++$i }}</td>
                        <td>Name : {{ $role->name }}</td>
                        <td class="ThreeBtns">
                            <a  href="{{ route('roles.show',$role->id) }}"><i class="btn btn-outline-success btn-sm bi bi-eye-fill"></i> </a>
                            @can('role-edit')
                                <a  href="{{ route('roles.edit',$role->id) }}"><i class="bi bi-pencil-square btn btn-outline-primary btn-sm"></i></a>
                            @endcan

                            {{-- @can('role-delete')
                            <form method="POST" action="{{ route('roles.destroy', $role->id) }}" style="display:inline">
                                @csrf
                                @method('DELETE')

                                <button type="submit" class="btn btn-danger btn-sm"><i class="fa-solid fa-trash"></i> Delete</button>
                            </form>
                            @endcan --}}
                        </td>
                    </tr>
                    @endforeach
                </tbody>

              </table> -->

             <!--  <div id="datatable" class="cardDs mobileShow">
                  <div class="cardBx">
                    @foreach ($roles as $key => $role)
                      <p>No : {{ ++$i }}</p>
                      <p>Name : {{ $role->name }}</p>
                      <p><div class="ThreeBtns"><a  href="{{ route('roles.show',$role->id) }}"><i class="btn btn-outline-success btn-sm bi bi-eye-fill"></i> </a>
                            @can('role-edit')
                                <a  href="{{ route('roles.edit',$role->id) }}"><i class="bi bi-pencil-square btn btn-outline-primary btn-sm"></i></a>
                            @endcan</div></p>
                    @endforeach
                  </div>
              </div> -->
              </div>
          </div>

        </div>
      </div>    
    </section>
    <script>
     $(document).ready(function() {
    var table = $('#datatable').DataTable({
        dom: 'Bfrtip',
        pageLength: 5,
        buttons: [
            {
                extend: 'excelHtml5',
                text: '<img src="{{asset('admin')}}/img/export.png" class="iconbt img-fluid" alt="export icon"> Export',  // Adding upload icon and "Export" text
                className: 'btn-export',  // Custom class for styling the button
                exportOptions: {
                    // Ensure all rows are exported (not just visible ones)
                    columns: ':visible',
                    format: {
                        body: function (data, row, column, node) {
                            // Use jQuery to remove span elements with 'mobileShow' class
                            var strippedData = $('<div>').html(data).find('.mobileShow').remove().end().text();
                            return strippedData.trim(); // Trim extra spaces
                        }
                    },
                    columns: ':not(:last-child)'
                }
            },
            {
                text: '<img src="{{asset('admin')}}/img/plus-icon.png" class="iconbt img-fluid" alt="plus icon"> Add New',  // Adding a plus icon with "Add Order" text
                className: 'btn-add-order greenBtn',  // Custom class for the button
                action: function(e, dt, node, config) {
                    window.location.href = "{{ route('roles.create') }}";  // Redirect to the order creation route
                }
            }
        ],
        language: {
            search: " ",  // Hide default 'Search' label
            searchPlaceholder: "Search"  // Placeholder for the search box
        }
    });
});


  </script>
@endsection

