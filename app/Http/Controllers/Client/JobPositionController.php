<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\ClientDepartment;
use Illuminate\Http\Request;
#Models
use App\Models\Department;
use App\Models\JobPosition;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class JobPositionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $departments = ClientDepartment::where('client_id',Auth::user()->client_id)
                                 ->with('department')
                                 ->paginate($this->count);
        $data = [
            'title' => 'View Job Positions',
            'user'  => Auth::user(),
            'departments'  => $departments,
        ];

        return view('department.position.view', $data);
    }
    public function add()
    {
        $departments = ClientDepartment::where('client_id',Auth::user()->client_id)
                       ->with('departments')->get();
        #return $departments;
        $data = [
            'title' => 'Add Job Position',
            'base_url' => env('APP_URL', 'http://omnibiz.local'),
            'user' => Auth::user(),
            'departments' => $departments
        ];
        return view('department.position.add', $data);
    }

    public function edit()
    {
        $data = [
            'title'    => 'Update Job Position',
            'base_url' => env('APP_URL', 'http://omnibiz.local'),
            'user'     => Auth::user()
        ];
        return view('department.position.edit', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title'         => 'required',
            'department_id' =>'required'
        ], [
            'title.required' => 'The job title field is required.',
            'department_id.required' => 'The department name field is required.'
            ]
        );

        $exist = JobPosition::where('title',$request->title)
            ->where('department_id',$request->department_id)
            ->first();

        if($exist)
        {
            return redirect(
                route('job.list.client')
            )->with('success', 'Job already exists!!');
        }

        $data                  =  $request->all();
        $data['department_id'] =  $request->department_id;
        $job                   = new JobPosition($data);
        $job->save();

        return redirect(
            route('job.list.client')
        )->with('success', 'Job was added successfully!');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $job = JobPosition::find($id);

        $this->validate($request, [
            'title' => 'sometimes|required',
            'department_id' =>'sometimes|numeric|required|exists:App\Models\Department,id'
        ], [
                'title.required' => 'The job title field is required.',
                'department_id.required' => 'The department name field is required.'
            ]
        );
        $exist = JobPosition::where('title',$request->title)
            ->where('department_id',$request->department_id)
            ->first();

        $exist && $this->error("Job Position already exists in this department!!");
        $request->input('title')           &&  $job->title             = $request->input('title');
        $request->input('department_id')   &&  $job->department_id     = $request->input('department_id');
        $job->save();

        $response=[
            'message' => 'Job updated successfully',
            'job'     => $job
        ];
        return response($response,201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete($id)
    {
        $job = JobPosition::find($id);
        $job->delete();
        return redirect(
            route('job.list.client')
        )->with('success', 'Job deleted successfully!');
    }
}
