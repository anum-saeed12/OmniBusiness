<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class LoginController extends Controller
{
    public $token = true;
    public function login(Request $request)
    {
       #validation of the password and email is declared and checked in request.
        $this->validate($request,[
            'password' => 'required|min:6',
            'email'    => 'required|email|min:4|max:191'

        ]);

        #If the user is found go to the next line otherwise it will show error.
        $user = User::where('email', $request->email)->first();
        $user || $this->error('User not found');

        #because we add our password in hash so we will read it in hash, if it is correct go to
        #the next line otherwise, stop here and show error
        Hash::check($request->password, $user->password) || $this->error('Password incorrect');

        $credentials = request(['password']);
        $credentials['email'] = $user->email;

        #attempting to login in through the credentials
        $token = auth()->attempt($credentials);

        $token || $this->error('The username or password is incorrect');
        $response = [
            'token' => $token,
            'user' => $user
        ];
        return response($response, 200);
    }
}
