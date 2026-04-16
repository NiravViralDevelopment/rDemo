<?php

namespace App\Http\Controllers;

use App\Models\Cities;
use App\Models\Countries;
use App\Models\ManageOrder;
use Illuminate\Http\Request;

class CitiesController extends Controller
{
    public function index(){
        $data = Cities::with('countries')->get();
        return view('cities.index',compact('data'));
    }

    public function Create(){
        $data = Countries::get();
        return view('cities.create',compact('data'));
    }

    public function Store(request $request){
        $request->validate([
            'title' => 'required',
        ]);
        $testimonial = new Cities();
        $testimonial->countries_id = $request->country_id;
        $testimonial->city_name    = $request->title;
        $testimonial->save();
        return redirect()->route('cities')->with('message','Data Created Successfully.');
    }

    public function Edit($id)
    { 
        $checkcity = ManageOrder::where('city_id',$id)->first();
        if(!empty($checkcity)){
            return redirect()->route('cities')->with('error','The selected city is already occupied. Kindly add a different city.');
        }
        $countries = Countries::get();
        $data = Cities::find($id);
        return view('cities.edit', compact('data','countries'));
    }

    public function Update(request $request, $id)
    {
        $request->validate([
            'title' => 'required',
        ]);
        $testimonial = Cities::find($id);
        $testimonial->countries_id = $request->country_id;
        $testimonial->city_name    = $request->title;
        $testimonial->save();
        return redirect()->route('cities')->with('message','Data Updated Successfully.');
    }

    public function getCities($countryId)
    {
        $cities = Cities::where('countries_id', $countryId)->get();
        return response()->json([
            'cities' => $cities
        ]);
    }


}
