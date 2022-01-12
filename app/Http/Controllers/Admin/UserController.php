<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

#Models
use App\Models\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::select('users.id','users.username','users.email','users.user_role','clients.name')
            ->orderBy('users.id','DESC')
            ->whereNotNull('users.client_id')
            ->join('clients','clients.id','=','users.client_id')
            ->paginate($this->count);
        $data = [
            'title'   => 'View Users',
            'user'    => Auth::user(),
            'users'   => $users,
        ];
        return view('admin.user.view',$data);
    }

    public function add()
    {
        $data = [
            'title'    => 'Add User',
            'base_url' => env('APP_URL', 'http://omnibiz.local'),
            'user'     => Auth::user()
        ];
        return view('admin.user.add', $data);
    }

    public function edit($id)
    {
        $user = User::select('users.id','users.username','users.email','users.user_role')->where('id',$id)->get();

        $data = [
            'title'    => 'Update User',
            'base_url' => env('APP_URL', 'http://omnibiz.local'),
            'users'    => $user
        ];
        return view('admin.user.edit', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'username'  => 'required|min:4|unique:App\Models\User',
            'password'  => 'required|min:6',
            'email'     => 'required|email|unique:App\Models\User',
            'user_role' => 'required|in:admin,client,manager,accountant,employee',
        ],[
            'user_role.required' => 'The privilege level field is required.'
        ]);

        $data = $request->all();
        $data['password']   = Hash::make($request->password);
        $data['created_by'] = Auth::id();
        $user = new User($data);
        $user->save() ;
        return redirect(
            route('user.list.admin')
        )->with('success', 'User was added successfully!');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'username'  => "required|unique:App\Models\User,username,{$id}",
            'password'  => "present",
            'email'     => "required|email|unique:App\Models\User,email,{$id}",
            'user_role' => 'present|in:admin,client,manager,accountant,employee',
        ],[
            'user_role.required' => 'The privilege level field is required.'
        ]);

        $user = User::find($id);
        $request->input('username')  && $user->username  = $request->input('username');
        $request->input('email')     && $user->email     = $request->input('email');
        $request->input('user_role') && $user->user_role = $request->input('user_role');
        $request->input('password')  && $user->password  = Hash::make($request->input('password'));
        $user->updated_by = Auth::id();
        $user->save() ;
        return redirect(
            route('user.list.admin')
        )->with('success', 'User was updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete($id)
    {
        $user = User::find($id)->delete();
        return redirect(
            route('user.list.admin')
        )->with('success', 'User deleted successfully!');
    }
}
