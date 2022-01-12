<?php

namespace App\Http\Controllers\Api\Client;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
#Models
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
        ($user = User::find(Auth::id()))->first()|| $this->error('No record found!');
        $result= User::select('*')
            ->where('client_id', $user->client_id)
            ->with('employee')
            ->get();
        $result->count() || $this->error("Employees do not exist");
        return response($result, 200);
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
        ($result= User::select('*')
            ->where('client_id', Auth::user()->client_id)
            ->where('employee_id',$id)
            ->first())|| $this->error('No record found!');

        $this->validate($request,[
            'username'  => 'sometimes|required|unique:App\Models\User|min:4',
            'user_role' => 'sometimes|required|in:manager,accountant,employee'
        ]);
        $request->input('username')  && $result->username  = $request->input('username');
        $request->input('user_role') && $result->user_role = $request->input('user_role');
        $result->updated_by = Auth::id();

        $result->save() || $this->error("User is not updated");
        return response($result, 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        ($result= User::select('*')
            ->where('client_id', Auth::user()->client_id)
            ->where('employee_id',$id)
            ->delete())|| $this->error('No record found!');
        return response(['message' => 'User deleted successfully'], 200);
    }
}
