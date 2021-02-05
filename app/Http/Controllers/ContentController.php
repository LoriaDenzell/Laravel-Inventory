<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use TJGazel\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Redirect;
use App\Content;
use Auth;
use DB;

class ContentController extends Controller
{
    public function index()
    {
        $content = Content::with(['user_modify'])->first();
        return view('content.index', compact('content'));
    }

    public function create()
    {
        return view('content.create');
    }

    public function store(Request $request)
    { 
        $validator = Validator::make($request->all(), [
            'org_name' => 'required',
            'org_contact' => 'required',
            'org_email' => 'required',
            'org_address' => 'required',
            'high_pct' => 'required|gt:ave_pct|gt:low_pct',
            'ave_pct' => 'required|gt:low_pct|lt:high_pct',
            'low_pct' => 'required|lt:ave_pct|lt:high_pct',
            'tax_pct' => 'required',
            'max_activities' => 'required'
        ]);

        if($validator->fails())
        {
            toastr()->warning('Failed to create CMS. Please check your inputs.');
            return Redirect::back()->withErrors($validator)->withInput();
        }

        $data = new Content();
        $data->org_name = $request->org_name;
        $data->org_contact = $request->org_contact;
        $data->org_email = $request->org_email;
        $data->org_address = $request->org_address;
        $data->high_pct = $request->high_pct; 
        $data->ave_pct = $request->ave_pct; 
        $data->low_pct = $request->low_pct; 
        $data->max_activities = $request->max_activities; 
        $data->max_activities = $request->tax_pct; 
        $data->user_modified = Auth::user()->id; 

        if($data->save())
        {
            toastr()->success('CMS Configuration saved successfully.');
            $content = Content::with(['user_modify'])->first();
            return view('content.index', compact('content'));
        }
        
        toastr()->error('Failed to save CMS Configuration.');
        return redirect()->back();
    }

    public function show($id)
    {
        return view('content.view');
    }

    public function edit($id)
    {
        $content = Content::with(['user_modify'])->first();
        return view('content.update', compact('content'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'org_name' => 'required',
            'org_contact' => 'required',
            'org_email' => 'required',
            'org_address' => 'required',
            'high_pct' => 'required|gt:ave_pct|gt:low_pct',
            'ave_pct' => 'required|gt:low_pct|lt:high_pct',
            'low_pct' => 'required|lt:ave_pct|lt:high_pct',
            'tax_pct' => 'required',
            'max_activities' => 'required'
        ]);

        if($validator->fails())
        {
            toastr()->warning('Failed to update CMS. Please check your inputs.');
            return Redirect::back()->withErrors($validator)->withInput();
        }

        $data = Content::find($id);

        $data->org_name = $request->org_name;
        $data->org_contact = $request->org_contact;
        $data->org_email = $request->org_email;
        $data->org_address = $request->org_address;
        $data->high_pct = $request->high_pct; 
        $data->ave_pct = $request->ave_pct; 
        $data->low_pct = $request->low_pct; 
        $data->max_activities = $request->max_activities; 
        $data->tax_pct = $request->tax_pct; 
        $data->user_modified = Auth::user()->id; 

        if($data->update())
        {
            toastr()->success('CMS Configuration updated successfully.');
            $content = Content::with(['user_modify'])->first();
            return view('content.index', compact('content'));
        }
        
        toastr()->error('CMS Configuration failed to update.');
        return redirect()->back();
    }

    public function destroy($id)
    {

    }
}
