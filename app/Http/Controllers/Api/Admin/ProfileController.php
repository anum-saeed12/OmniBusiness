<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function show()
    {
        ($user = User::find(Auth::id())) || $this->error("User not found");
        return response($user,200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        ($user = User::find(Auth::id())) || $this->error("User not found");

        $this->validate($request, [
            'username'  => 'sometimes|required|unique:App\Models\User|min:4',
            'password'  => 'sometimes|required',
            'email'     => 'sometimes|required|unique:App\Models\User'
        ]);

        $request->input('username')  &&  $user->username  = $request->input('username');
        $request->input('email')     &&  $user->email     = $request->input('email');
        $request->input('password')  &&  $user->password  = Hash::make($request->input('password'));
        $user->updated_by = Auth::id();

        $user->save() || $this->error("User profile is not updated");
        return response($user,200);

    }

}
