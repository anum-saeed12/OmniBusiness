<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

#Models
use App\Models\Department;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $departments = Department::orderBy('id','DESC')
            ->paginate($this->count);
        $data = [
            'title'       => 'View Departments',
            'user'        => Auth::user(),
            'departments' => $departments,
        ];
        return view('admin.department.view',$data);
    }

    public function add()
    {
        $data = [
            'title' => 'Add Department',
            'base_url' => env('APP_URL', 'http://omnibiz.local'),
            'user' => Auth::user()
        ];
        return view('admin.department.add', $data);
    }


    public function edit($id)
    {
        $departments = Department::orderBy('id','DESC')->paginate($this->count);
        $department = Department::find($id);
        $data = [
            'title'       => 'Update Department',
            'base_url'    => env('APP_URL', 'http://omnibiz.local'),
            'user'        => Auth::user(),
            'department'  => $department,
            'departments' => $departments,
        ];
        return view('admin.department.edit', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     */

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required'
        ]);

        $exist = Department::where('name',$request->name)->first();

        if($exist)
            return redirect(
                route('department.list.admin')
            )->with('error', 'Department already exists !');

        $data               = $request->all();
        $department         = new Department($data);
        $department->save();

        return redirect(
            route('department.list.admin')
        )->with('success', 'Department was added successfully!');
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {

        $department = Department::find($id);
        $request->validate([
            'name' => 'required'
        ]);

        $exists = Department::where('name',$request->name)->first();
        if($exists)
            return redirect(
                route('department.list.admin')
            )->with('error', 'Department name already exists cannot update !');

        $request->input ('name')    &&    $department->name = $request->input ('name');
        $department->save();

        return redirect(
            route('department.list.admin')
        )->with('success', 'Department Updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     */
    public function delete($id)
    {
        $department= Department::find($id)->delete();
        return redirect(
            route('department.list.admin')
        )->with('success', 'Department deleted successfully!');
    }
}
