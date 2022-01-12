<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VendorController extends Controller
{
    public function index()
    {
        $client_id = Auth::user()->client_id;
        $vendor    = Company::orderBy('id','DESC')->paginate($this->count);
        $data = [
            'title'   => 'View Vendors',
            'user'    => Auth::user(),
            'vendors'   => $vendor,
        ];
        return view('vendor.view',$data);
    }

    public function add()
    {
        $data = [
            'title'    => 'Add Vendor',
            'base_url' => env('APP_URL', 'http://omnibiz.local'),
            'user'     => Auth::user()
        ];
        return view('vendor.add', $data);
    }

    public function edit($id)
    {
        $vendor = Company::where('id',$id)->first();

        $data = [
            'title'    => 'Update Vendor',
            'base_url' => env('APP_URL', 'http://omnibiz.local'),
            'user'     => Auth::user(),
            'vendor'   => $vendor
        ];
        return view('vendor.edit', $data);
    }
    public function store(Request $request)
    {

        $request->validate([
            'name'       => 'required|min:4|unique:App\Models\Company',
            'address_1'  => 'required',
            'address_2'  => 'required',
            'phone_num'  => 'required',
            'personal_email'=> 'required',
        ]);

        $data               = $request->all();
        $data['client_id']  = Auth::user()->client_id;
        $vendor = new Company($data);
        $vendor->save() ;
        return redirect(
            route('vendor.list.client')
        )->with('success', 'vendor was added successfully!');
    }
    public function update(Request $request,$id)
    {
        $request->validate([
            'name'       => 'present|min:4|unique:App\Models\Company',
            'address_1'  => 'present',
            'address_2'  => 'present',
            'phone_num'  => 'present',
            'email'      => 'sometimes',
        ]);

        $vendor = Company::find($id);
        $request->input('name')         && $vendor->name       = $request->input('name');
        $request->input('address_1')    && $vendor->address_1  = $request->input('address_1');
        $request->input('address_2')    && $vendor->address_2  = $request->input('address_2');
        $request->input('phone_num')    && $vendor->phone_num  = $request->input('phone_num');
        $request->input('email')        && $vendor->email      = $request->input('email');
        $vendor->save() ;
        return redirect(
            route('vendor.list.client')
        )->with('success', 'vendor was updated successfully!');
    }

    public function delete($id)
    {
        $vendor = Company::find($id)->delete();
        return redirect(
            route('vendor.list.client')
        )->with('success', 'Vendor deleted successfully!');
    }
}
