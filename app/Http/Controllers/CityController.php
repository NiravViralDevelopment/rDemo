<?php

namespace App\Http\Controllers;

use App\Models\Cities;
use App\Models\City;
use App\Models\Countries;
use App\Models\ManageOrder;
use Illuminate\Http\Request;

class CityController extends Controller
{
    public function index(){
        $data= City::with('countries','state')->get();
        return view('city.index',compact('data'));        
    }

    public function Create(){
        $country = Countries::get(); 
        $state = Cities::get();
        return view('city.create',compact('country','state'));
    }

    public function Store(request $request){
        // dd($request) ;       
        $cityinfo = new City();
        $cityinfo->countries_id  = $request->country_id;
        $cityinfo->state_id      = $request->state_id;
        $cityinfo->city_name     = $request->city_name;
        $cityinfo->save();
        return redirect()->route('city')->with('message','Data Created Successfully.');
    }

    public function Edit($id)
    { 
        
        // $checkcity = ManageOrder::where('city_id',$id)->first();
        // if(!empty($checkcity)){
        //     return redirect()->route('city')->with('error','The selected city is already occupied. Kindly add a different city.');
        // }
        $countries = Countries::get();
        $state = Cities::get();
        $data = City::find($id);
        return view('city.edit', compact('data','countries' ,'state'));
    }

    public function Update(request $request, $id)
    {
       
        $cityinfo = City::find($id);
        $cityinfo->countries_id  = $request->country_id;
        $cityinfo->state_id      = $request->state_id;
        $cityinfo->city_name     = $request->city_name;
        $cityinfo->save();
        return redirect()->route('city')->with('message','Data Updated Successfully.');
    }


    public function getCities($countryId)
    {
        $cities = Cities::where('countries_id', $countryId)->get();
        return response()->json([
            'cities' => $cities
        ]);
    }

}
