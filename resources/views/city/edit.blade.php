@extends('layouts.admin.app')
<script src="https://code.jquery.com/jquery-1.11.3.js"></script>
@section('content')

<div class="row">
<div class="col-lg-12">
<div class="card cardPdd">
    <div class="card-body topText">
        <div class="col-lg-12 margin-tb">
            <div class="flexBdy">
            <div class="pull-left">
                <h5 class="card-title"> Edit</h5>
            </div>

            <div class="pull-right">
                <a class="btn btn-sm mb-2 comnBtn whtTxt borderBtn" href="{{ route('city') }}"> Back</a>
            </div>
            </div>
        </div>
    </div>
    <section class="section frmGrp">
      <div class="row">
        <div class="col-lg-12">


              <!-- Horizontal Form -->
              <form action="{{ route('city-update',$data->id) }}" method="POST" enctype="multipart/form-data"> @csrf
                
                <div class="row mb-3">
                    <label for="inputEmail3" class="col-sm-1 col-form-label strng flexCntr"> Country <span class="text-danger">*</span></label>
                    <div class="col-sm-5">
                        <select name="country_id" id="country_id" class="form-control required">
                            <option value="">Select Country</option>
                            @foreach ($countries as $row)
                                <option value="{{ $row->id }}" @if($data->countries_id == $row->id) selected  @endif >{{ $row->country_name }}</option>
                            @endforeach
                        </select>
                        @error('country_id')
                            <div class="text-danger">{{ $country_id }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="city" class="col-sm-1 col-form-label strng flexCntr">Select State <span class="text-danger">*</span></label>
                    <div class="col-sm-5">
                        <select id="state_id" name="state_id" class="form-select form-control required">
                            <option value="">Select State</option>
                        </select>
                    </div>
                    @error('state_id')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="row mb-3">
                    <label for="inputEmail3" class="col-sm-1 col-form-label strng flexCntr">City<span class="text-danger">*</span></label>
                    <div class="col-sm-5">
                      <input type="text" name="city_name" value="{{ $data->city_name }}" class="form-control required" id="inputText">
                     </div>
                  </div>

                
                <div class="text-left mt50 pddLftStLow">
                  <button type="submit" class="btn btn-sm comnBtn whtTxt">Update</button>
                  
                </div>
              </form>

          </div>
        </div>

    </section> 
    </div> 
  </div>
</div>
<script>
    $(document).ready(function() {
        var selectedCountryId = '{{ $data->countries_id }}'; // Get selected country ID from backend data
        var selectedCityId = '{{ $data->state_id }}'; // Get selected city ID from backend data
        // Load cities when the page loads, based on the pre-selected country
        if (selectedCountryId) {
            loadCities(selectedCountryId, selectedCityId);
        }
        function loadCities(countryId, selectedCityId) {
        // Clear the city dropdown
        $('#state_id').html('<option value="">Select City</option>');

        $.ajax({
            url: '/get-cities/' + countryId, // Assuming this URL returns cities based on country_id
            type: 'GET',
            success: function(data) {
                $.each(data.cities, function(key, city) {
                    var isSelected = '';

                    // Compare city ID and selectedCityId (ensuring types match)
                    if (String(city.id) === String(selectedCityId)) {
                        isSelected = 'selected';
                    }

                    // Append the city option
                    $('#state_id').append('<option value="'+ city.id +'" ' + isSelected + '>'+ city.city_name +'</option>');
                });
            },
            error: function(xhr) {
                console.error("Error loading cities:", xhr);
            }
        });
    }

    //on change event 
    $('#country_id').on('change', function() {
        var countryId = $(this).val();
        if (countryId) {
            loadCities(countryId, null); // Load cities for selected country, with no pre-selected city
        } else {
            $('#city').html('<option value="">Select City</option>'); // Clear city dropdown if no country selected
        }
    });

    });
</script>

@endsection





