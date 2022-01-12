<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

#Models
use App\Models\User;
use App\Models\Client;

class ClientController extends Controller
{
    /*
     * Display a listing of the resource.
     *
     */
    public function index()
    {
        $clients = User::wherenotNull('client_id')
            ->whereNull('employee_id')
            ->with('client')
            ->orderBy('id', 'DESC')
            ->paginate($this->count);
        $data = [
            'title'   => 'View Clients',
            'user'    => Auth::user(),
            'clients' => $clients,
        ];
        return view('admin.client.view',$data);
    }

    public function add()
    {
        $data = [
            'title'    => 'Add Client',
            'base_url' => env('APP_URL', 'http://omnibiz.local'),
            'user'     => Auth::user(),
        ];
        return view('admin.client.add', $data);
    }

    public function edit($id)
    {
        $client = User::where('client_id', $id)->whereNull('employee_id')->with('client')->first();
        $data = [
            'title'    => 'Update Client',
            'base_url' => env('APP_URL', 'http://omnibiz.local'),
            'user'     => Auth::user(),
            'client'   => $client
        ];
        return view('admin.client.edit', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'           => 'required|min:4',
            'address_1'      => 'required',
            'address_2'      => 'required',
            'mobile'         => 'required|digits:13|unique:App\Models\Client,mobile',
            'landline'       => 'present',
            'official_email' => 'present',
            'ntn_number'     => 'present',
            'avatar'         => 'present',
            'license'        => 'present',
            'website'        => 'present|unique:App\Models\Client,website',
            'overview'       => 'required',
            'username'       => 'required|unique:App\Models\User|min:4',
            'password'       => 'required',
            'email'          => 'required|email|unique:App\Models\User',
            'prefix'         => 'required|unique:App\Models\Client,prefix'
        ],[
            'address_1.required' => 'The address line 1 field is required.',
            'address_2.required' => 'The address line 2 field is required.',
            'overview.required' => 'The bio field is required.',
            'prefix.required' => 'The prefix field is not unique.'
        ]);

        $data               = $request->all();
        $data['created_by'] = Auth::id();
        $client             = new Client($data);
        $client->save();

        $data               = $request->all();
        $data['password']   = Hash::make($request->password);
        $data['created_by'] = Auth::id();
        $data['client_id']  = $client->id;
        $data['user_role']  = 'client';
        $user               = new User($data);
        $user->save();
        return redirect(
            route('client.list.admin')
        )->with('success', 'Client was added successfully!');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $client = User::where('client_id', $id)->whereNull('employee_id')->value('id');

        $request->validate([
            'name'           => "required|min:4|unique:App\Models\Client,name,{$id}",
            'address_1'      => 'required',
            'address_2'      => 'required',
            'mobile'         => "required|digits:11|unique:App\Models\Client,mobile,{$id}",
            'landline'       => "present|unique:App\Models\Client,landline,{$id}",
            'official_email' => "present|email|unique:App\Models\Client,official_email,{$id}",
            'ntn_number'     => 'present',
            'avatar'         => 'present',
            'license'        => 'present',
            'website'        => "sometimes|unique:App\Models\Client,website,{$id}",
            'overview'       => 'required',
            'username'       => "required|min:4|unique:App\Models\User,username,{$client}",
            'password'       => "present",
            'email'          => "required|email|unique:App\Models\User,email,{$client}",
            'prefix'         => 'required|unique:App\Models\Client,prefix'
        ],[
            'address_1.required' => 'The address line 1 field is required.',
            'address_2.required' => 'The address line 2 field is required.',
            'overview.required' => 'The bio field is required.',
            'prefix.required' => 'The prefix field is not unique.'
        ]);

        $client = User::where('id', $id)->with('client')->first();

        $request->input('name')       && $client->client->name         = $request->input('name');
        $request->input('ntn_number') && $client->client->ntn_number   = $request->input('ntn_number');
        $request->input('location')   && $client->client->location     = $request->input('location');
        $request->input('license')    && $client->client->license      = $request->input('license');
        $request->input('website')    && $client->client->website      = $request->input('website');
        $request->input('overview')   && $client->client->overview     = $request->input('overview');
        $request->input('username')   && $client->username             = $request->input('username');
        $request->input('email')      && $client->email                = $request->input('email');
        $request->input('prefix')     && $client->prefix               = $request->input('prefix');
        $client->client->updated_by = Auth::id();
        $client->updated_by = Auth::id();
        $client->save();

        return redirect(
            route('client.list.admin')
        )->with('success', 'Client was updated successfully!');

    }
    /**
     * Remove the specified resource from storage.
     *
     */
    public function delete($id)
    {
        $user_deleted = User::where('client_id', $id)->delete();
        $client_deleted = Client::find($id)->delete();
        return redirect(
            route('client.list.admin')
        )->with('success', 'Client was deleted successfully!');
    }


    public function status($client_id,$action)
    {
        $client = Client::find($client_id);
        $allowed = ['activate','deactivate'];
        # Checks if the status is to activate the user or de-activate the user
        if(!in_array($action, $allowed)) return redirect()->back()->with('error', 'Unknown status!');

        $client->active = $action=='activate'?1:0;
        #$client->client->updated_by = Auth::id();
        $client->updated_by = Auth::id();
        $client->save();

        return redirect()->back()->with('success', 'Client updated successfully!');
    }

    public function activate($client_id, Request $request)
    {

        $request->validate([
            'amount' => 'required'
        ]);

        $client = Client::find($client_id);

        $client->active = 1;
        #$client->client->updated_by = Auth::id();
        $client->updated_by = Auth::id();
        $client->save();

        $subscription = Subscription::select('id','type_of_subscription as type')
            ->where('client_id', $client_id)
            ->whereNull('membership_start')
            ->whereNull('membership_end')
            ->whereNull('last_paid_amount')
            ->whereNotNull('receipt')
            ->firstOrFail();
        $subscription = Subscription::find($subscription->id);
        $subscription->membership_start = Carbon::today();
        $subscription->membership_end = $subscription->type=='yearly'?Carbon::today()->addYear():Carbon::today()->addMonth();
        $subscription->last_paid_amount = $request->amount;
        $subscription->approved = 1;
        $subscription->save();

        return redirect()->back()->with('success', 'Client updated successfully!');
    }

    public function disapprove($client_id, Request $request)
    {

        $request->validate([
            'description' => 'required'
        ]);

        if($request->input('disable')) {
            $client = Client::find($client_id);
            $client->active = 0;
            $client->updated_by = Auth::id();
            $client->save();
        }

        $subscription = Subscription::select('id','type_of_subscription as type')
            ->where('client_id', $client_id)
            ->whereNull('membership_start')
            ->whereNull('membership_end')
            ->whereNull('last_paid_amount')
            ->whereNotNull('receipt')
            ->where('approved', 2)
            ->firstOrFail();
        $subscription = Subscription::find($subscription->id);
        $subscription->approved = 0;
        $subscription->description = $request->description;
        $subscription->save();

        return redirect()->back()->with('success', 'Request has been disapproved!');
    }
}
