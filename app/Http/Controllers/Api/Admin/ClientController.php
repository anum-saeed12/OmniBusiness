<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\Request;

#Models
use App\Models\User;
use App\Models\Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $client=Client::all();
        $client->count()||$this->error('Client does not available');

        return response($client,200);
    }

    public function add()
    {
        $data = [
            'title' => 'Add Department',
            'base_url' => env('APP_URL', 'http://omnibiz.local'),
            'name' => 'Anum (Administrator)'
        ];
        return view('admin.department.add', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name'       => 'required|min:4',
            'ntn_number' => 'sometimes|required|numeric',
            'avatar'     => 'sometimes',
            'license'    => 'sometimes|required|numeric',
            'website'    => 'sometimes|required|url',
            'overview'   => 'required',
            'username'   => 'required|unique:App\Models\User|min:4',
            'password'   => 'required',
            'email'      => 'required|email|unique:App\Models\User'
        ]);

        $data               = $request->all();
        $data['created_by'] = Auth::id();
        $client             = new Client($data);
        $client->save() || $this->error("Client is not inserted!");

        $data               = $request->all();
        $data['password']   = Hash::make($request->password);
        $data['created_by'] = Auth::id();
        $data['client_id']  = $client->id;
        $data['user_role']  = 'client';
        $user               = new User($data);
        $user->save() || $this->error("Client user is not inserted!");

        $response=[
            'message' =>'Client added successfully',
            'client'  => $client,
            'user'    => $user
        ];
        return response($response,201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user    = User::where('client_id', $id)->value('id');
        ($user   = User::find($user)) || $this->error("User not found!");
        ($client = Client::find($id)) || $this->error("Client not found!");
        $this->validate($request, [
            'name'       => 'sometimes|required|min:4',
            'ntn_number' => 'sometimes|required|numeric',
            'license'    => 'sometimes|required|numeric',
            'username'   => 'sometimes|required|min:4',
            'email'      => 'sometimes|required|email|unique:App\Models\User'
        ]);

        $request->input('name')       && $client->name        = $request->input('name');
        $request->input('ntn_number') && $client->ntn_number  = $request->input('ntn_number');
        $request->input('license')    && $client->license     = $request->input('license');
        $client->updated_by = Auth::id();
        $client->save() || $this->error("client is not updated!");

        $request->input('username') && $user->username  = $request->input('username');
        $request->input('email')    && $user->email     = $request->input('email');
        $user->updated_by = Auth::id();
        $user->save() || $this->error("User is not updated");

        $response=[
            'message' =>'Client updated successfully',
            'user'=> $user,
            'client' => $client
        ];
        return response($response,200);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        ($user_deleted = User::where('client_id', $id)->delete()) || $this->error("Error deleting the user of the client");
        ($client_deleted = Client::find($id)->delete()) || $this->error("Client not deleted");

        return response(['message' => 'Client deleted successfully'],200);
    }
}
