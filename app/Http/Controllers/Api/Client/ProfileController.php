<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
#Models
use App\Models\User;
use App\Models\Client;

class ProfileController extends Controller
{
    public function show()
    {
        ($client = Client::find(Auth::user()->client_id)) || $this->error('No record found!');
        $response = [
            'user'   => Auth::user(),
            'client' => $client
        ];
        return response($response, 200);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request): \Illuminate\Http\Response
    {

        ($user = User::find(Auth::id())) || $this->error("User not found");
        ($client = Client::find(Auth::user()->client_id))|| $this->error('No record found!');

        $this->validate($request, [
            'name'       => 'sometimes|required|min:4',
            'ntn_number' => 'sometimes|required|numeric',
            'avatar'     => 'sometimes',
            'license'    => 'sometimes|required|numeric',
            'website'    => 'sometimes|required|url',
            'overview'   => 'sometimes|required',
            'password'   => 'sometimes|required',
            'email'      => 'sometimes|required|email|unique:App\Models\User',
        ]);

        $request->input('name')       &&  $client->name        = $request->input('username');
        $request->input('ntn_number') &&  $client->ntn_number  = $request->input('ntn_number');
        $request->input('avatar')     &&  $client->avatar      = $request->input('avatar');
        $request->input('license')    &&  $client->license     = $request->input('license');
        $request->input('website')    &&  $client->website     = $request->input('website');
        $request->input('overview')   &&  $client->overview    = $request->input('overview');
        $client->save() ||$this->error("Your info was not updated");
        $client->updated_by = Auth::id();

        $request->input('email')      &&  $user->email         = $request->input('email');
        $request->input('password')   &&  $user->password      = Hash::make($request->input('password'));
        $user->updated_by = Auth::id();

        $user->save() || $this->error("Your user info was not updated");

        $response = [
            'user' => $user,
            'client' => $client
        ];
        return response($response,201);
    }
}

