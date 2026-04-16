<?php

namespace App\Http\Controllers;

use App\Models\Countries;
use App\Models\ManageOrder;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class CountriesController extends Controller
{
    public function index()
    {
        $data = Countries::get();
        return view('countries.index', compact('data'));
    }

    public function Create()
    {
        return view('countries.create');
    }

    public function Store(request $request)
    {
        $request->validate([
            'title' => 'required',
        ]);
        $testimonial = new Countries();
        $testimonial->country_name = $request->title;
        $testimonial->code = $request->code;
        $testimonial->save();
        return redirect()->route('countries')->with('message', 'Data Created Successfully.');
    }

    public function Edit($id)
    {
        $checkcity = ManageOrder::where('country_id',$id)->first();
        if(!empty($checkcity)){
            return redirect()->route('countries')->with('error','The selected country is already occupied. Kindly add a different city.');
        }
        
        $data = Countries::find($id);
        return view('countries.edit', compact('data'));
    }

    public function Update(request $request, $id)
    {
        $testimonial = Countries::find($id);
        $request->validate([
            'title' => 'required',
        ]);
        $testimonial->country_name = $request->title;
        $testimonial->code = $request->code;
        $testimonial->save();
        return redirect()->route('countries')->with('message', 'Data Updated Successfully.');
    }
}
