<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $department=Department::all();
        $department->count() || $this->error("No department available");

        return response($department,200);
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
            'name' => 'required'
        ]);

        $exist = Department::where('name',$request->name)->first();

        if(empty($exist))
        {
            $data               = $request->all();
            $department         = new Department($data);
            $department->save() || $this->error("Department is not inserted!");

            $response = [
                'Message' => 'Department is added',
                'Department' => $department,
            ];
            return response($response, 200);
    }

        $response = [
            'Message' => 'Department is already available'
        ];
        return response($response, 200);
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
        $department = Department::find($id) ;
        $department || $this->error('Department is not available');
        $this->validate($request, [
            'name' => 'sometimes|required|exists:App\Models\Department,name'
        ]);
        $request->input ('name')    && $department->name = $request->input ('name');

        $exists = Department::where('name',$request->name)->get();
        $exists && $this->error('Department is already Available');

        $department->save() || $this->error("Department is not inserted!");

        $response = [
            'Message' => 'Department is updated',
            'Department' => $department,
        ];
        return response($response, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        $department= Department::find($id);
        $department || $this->error('Department is not available');
        $department->delete();
        $response = [
            'Message' => 'Department deleted successfully!!'
        ];
        return response($response, 200);
    }
}
