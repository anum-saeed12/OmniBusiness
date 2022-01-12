<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
// Models
use App\Models\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = User::all();
        return response($user, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'username'  => 'required|min:4',
            'password'  => 'required',
            'email'     => 'required|email|unique:App\Models\User',
        ]);

        $data = $request->all();
        $data['password']   = Hash::make($request->password);
        $data['created_by'] = Auth::id();
        $data['user_role']  = 'admin';
        $user = new User($data);
        $user->save() || $this->error("User is not inserted!");
        return response($user, 201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        ($user = User::find($id)) || $this->error("User not found");

        $this->validate($request, [
            'username'  => 'sometimes|required|unique:App\Models\User|min:4',
            'password'  => 'sometimes|required',
            'email'     => 'sometimes|required|email|unique:App\Models\User',
        ]);

        $request->input('username')  && $user->username  = $request->input('username');
        $request->input('email')     && $user->email     = $request->input('email');
        $request->input('password')  && $user->password  = Hash::make($request->input('password'));
        $user->updated_by = Auth::id();

        $user->save() || $this->error("User is not updated");
        return response($user, 201,);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        ($user = User::find($id))|| $this->error("User is not deleted");
        $user->delele();
        return response(['message' => 'User deleted successfully'], 200);
    }
}
