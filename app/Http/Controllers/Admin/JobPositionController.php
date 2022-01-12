<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

#Models
use App\Models\Department;
use App\Models\JobPosition;
class JobPositionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $jobs = JobPosition::with('department')
            ->orderBy('id','DESC')
            ->paginate($this->count);
        $data = [
            'title' => 'View Job Positions',
            'user'  => Auth::user(),
            'jobs'  => $jobs,
        ];
        return view('admin.department.position.view', $data);
    }

    public function add()
    {
        $departments = Department::orderBy('id','DESC')->get();
        $data = [
            'title'       => 'Add Job Position',
            'base_url'    => env('APP_URL', 'http://omnibiz.local'),
            'user'        => Auth::user(),
            'departments' => $departments
        ];
        return view('admin.department.position.add', $data);
    }

    public function edit($id)
    {
        $position = JobPosition::where('id',$id)->with('department')->first();
        $departments = Department::all();
        $data = [
            'title'       => 'Update Job Position',
            'base_url'    => env('APP_URL', 'http://omnibiz.local'),
            'user'        => Auth::user(),
            'position'    => $position,
            'departments' => $departments
        ];
        return view('admin.department.position.edit', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title'         => 'required|unique:App\Models\JobPosition,title',
            'department_id' => 'required'
        ],[
            'title.required'         => 'The job title field is required.',
            'department_id.required' => 'The department name field is required.'
        ]);

        $exist = JobPosition::where('title',$request->title)
            ->where('department_id',$request->department_id)
            ->first();
        $exist && $this->error("Job Position already exists in this department!!");
        $data                  =  $request->all();
        $data['department_id'] =  $request->department_id;
        $job                   = new JobPosition($data);
        $job->save();
        return redirect(
            route('job.list.admin')
        )->with('success', 'Job was added successfully!');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $job = JobPosition::find($id);

        $request->validate([
            'title'         => "required|unique:App\Models\JobPosition,title,{$id}",
            'department_id' => 'required'
        ],[
            'title.required'         => 'The job title field is required.',
            'department_id.required' => 'The department name field is required.'
        ]);

        $request->input('title')           &&  $job->title             = $request->input('title');
        $request->input('department_id')   &&  $job->department_id     = $request->input('department_id');
        $job->save();

        return redirect(
            route('job.list.admin')
        )->with('success', 'Job updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete($id)
    {
        $job = JobPosition::find($id);
        return redirect(
            route('job.list.admin')
        )->with('success', 'Job deleted successfully!');
    }
}
