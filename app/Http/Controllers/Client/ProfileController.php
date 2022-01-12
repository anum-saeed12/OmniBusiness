<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
#Models
use App\Models\User;
use App\Models\Client;
use Illuminate\Support\Facades\Storage;
use Ramsey\Uuid\Uuid;

class ProfileController extends Controller
{
    public function show()
    {
        $profile = Client::find(Auth::user()->client_id);
        $data = [
            'title'       => 'Profile',
            'user'        => Auth::user(),
            'profile'     => $profile,
        ];

        return view('client.profile.view', $data);
    }

    public function update(Request $request): \Illuminate\Http\Response
    {
        ($user = User::find(Auth::id())) ;
        ($client = Client::find(Auth::user()->client_id));

        $request->validate([
            'name'       => 'present|min:4',
            'ntn_number' => 'present|numeric',
            'avatar'     => 'present',
            'license'    => 'present|numeric',
            'website'    => 'present|url',
            'overview'   => 'present',
            'location'   => 'present',
            'password'   => 'present',
            'email'      => 'present|email|unique:App\Models\User',
        ]);

        $request->input('name')       &&  $client->name        = $request->input('username');
        $request->input('ntn_number') &&  $client->ntn_number  = $request->input('ntn_number');
        $request->input('avatar')     &&  $client->avatar      = $request->input('avatar');
        $request->input('location')   &&  $client->location    = $request->input('location');
        $request->input('license')    &&  $client->license     = $request->input('license');
        $request->input('website')    &&  $client->website     = $request->input('website');
        $request->input('overview')   &&  $client->overview    = $request->input('overview');
        $client->save() ||$this->error("Your info was not updated");
        $client->updated_by = Auth::id();

        $request->input('email')      &&  $user->email         = $request->input('email');
        $request->input('password')   &&  $user->password      = Hash::make($request->input('password'));
        $user->updated_by = Auth::id();

        $user->save();

        $response = [
            'user' => $user,
            'client' => $client
        ];
        return response($response,201);
    }

    public function edit()
    {
        $client = User::where('client_id', Auth::user()->client_id)->whereNull('employee_id')->with('client')->first();
        $data = [
            'title'    => 'Update Profile',
            'base_url' => env('APP_URL', 'http://omnibiz.local'),
            'user'     => Auth::user(),
            'client'   => $client
        ];
        return view('client.profile.edit', $data);
    }

    public function hello()
    {
        $client = Client::find(Auth::user()->client_id);
        $client->last_logged_in = Carbon::now();
        if ($client->save()) return redirect(route('profile.edit.client'))->with('hello', "Welcome to OmniBusiness Solution! Lets start with providing all your information.");
    }

    public function subscription()
    {
        $subscription = Subscription::where('client_id', Auth::user()->client_id)->orderBy('id', 'DESC')->get();
        $data = [
            'title'        => 'Subscription',
            'base_url'     => env('APP_URL', 'http://omnibiz.local'),
            'user'         => Auth::user(),
            'subscription' => $subscription
        ];
        return view('client.subscription', $data);
    }

    public function saveReceipt(Request $request)
    {

        $request->validate([
            'receipt' => 'required|file',
            'type_of_subscription' => 'required|in:yearly,monthly'
        ]);

        $file = $request->file('receipt');
        $filename = Uuid::uuid4().".{$file->extension()}";
        $private_path = $file->storeAs(
            'public/receipts', $filename
        );
        $public_path = Storage::url("receipts/$filename");

        $subscription = new Subscription();
        $subscription->client_id = Auth::user()->client_id;
        $subscription->next_payment_date = $request->type_of_subscription == 'yearly'?Carbon::today()->addYear():Carbon::today()->addMonth();
        $subscription->last_payment_date = Carbon::today();
        $subscription->type_of_subscription = $request->type_of_subscription;
        $subscription->receipt = $public_path;
        $subscribed = $subscription->save();

        return redirect()->back()->with('success', 'Receipt has been submitted!');
    }
}

