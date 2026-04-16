
@foreach($data as $key => $row)
    
    {{$key+1}}
<h1>  first name  : {{ $row->client_name}}</h1>
<h1>  last name  : {{ $row->client_last_name}}</h1>
<h1>  Address  : {{ $row->address}}</h1>
<h1>  city  : {{ $row->city_id}}</h1>
<h1>  email  : {{ $row->email}}</h1>
<h1>  mobile No  : {{ $row->mobile_number}} </h1>
<h1>  VIN No  :{{ $row->vin_record_number}}</h1>
<h1>  Box Code  : </h1>
<h1>  ID  : {{ $row->Eid_no}}</h1>
<h1>  Date  : {{ $row->invoice_date}}</h1>

                                                            
<h1> Signature   :  <img src="{{ asset('upload/' . $row->digital_signature) }}" height="100" width="100" alt="Signed POD">
</h1>



@endforeach