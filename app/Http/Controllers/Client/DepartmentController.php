<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

#Models
use App\Models\ClientDepartment;
use App\Models\Department;
use App\Models\Employee;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     */
    public function index()
    {
        /*$departments = ClientDepartment::where('client_id',Auth::user()->client_id)
                    ->with('department')
                   ->paginate($this->count);*/
        $departments = Department::select(
            DB::raw("departments.name as department"),
            DB::raw("COUNT(employees.id) as total"),
            DB::raw("departments.id as id")
        )
            ->leftJoin('job_positions','job_positions.department_id','=','departments.id')
            ->leftJoin('client_department','client_department.department_id','=','departments.id')
            ->leftJoin("employees",function($join) {
                $join->on("employees.position_id","=","job_positions.id")
                    ->on("employees.client_id","=","client_department.client_id");
                    })
            ->where('client_department.client_id',Auth::user()->client_id)
            ->groupBy('client_department.department_id','departments.name','departments.id')
            ->paginate($this->count);
        $data = [
            'title'       => 'View Departments',
            'user'        => Auth::user(),
            'departments' => $departments,
        ];
        return view('department.view',$data);
    }
    public function add()
    {
        $deparment = Department::all();
        $data = [
            'title'     => 'Add Department',
            'base_url'  => env('APP_URL', 'http://omnibiz.local'),
            'user'      => Auth::user()
        ];
        return view('department.add', $data);
    }


/*    public function edit()
    {
        $data = [
            'title'    => 'Update Department',
            'base_url' => env('APP_URL', 'http://omnibiz.local'),
            'user'     => Auth::user()
        ];
        return view('department.edit', $data);
    }*/

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required'
        ]);

        $fetch_department = Department::where('name', $request->name)->first();
        # Checks if the department does not exist
        if(empty($fetch_department))
        {
            # Creates a new record for the department
            $data               = $request->all();
            $department         = new Department($data);
            if (!$department->save())
                return redirect(route('department.list.client'))
                    ->with('error', 'Department was not saved');
            $fetch_department = $department;
        }
        $result = ClientDepartment::where('client_id',Auth::user()->client_id)
            ->where('department_id',$fetch_department->id)->first();
        if($result)
        {
            return redirect(
                route('department.list.client')
            )->with('success', 'Department already exists!');
        }

        # Create the connection to the department
        $connection = new ClientDepartment([
            'client_id'     => Auth::user()->client_id,
            'department_id' => $fetch_department->id
        ]);

        if (!$connection->save())
            return redirect(route('department.list.client'))
                            ->with('error', 'Department could not be linked');

        return redirect(route('department.list.client'))->with('success', 'Department was saved');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function delete($id)
    {
        $result= ClientDepartment::where('client_id',Auth::user()->client_id)
                 ->where('department_id',$id)
                 ->delete();
        return redirect(
            route('department.list.client')
        )->with('success', 'Department deleted successfully!');
    }
}
