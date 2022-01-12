<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
#Models
use App\Models\Department;
use App\Models\JobPosition;
class JobPositionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $job = JobPosition::with('department')->get();

        $job->count() || $this->error('Jobs are not available');

        return response($job,200);
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
            'title' => 'required',
            'department_id' =>'required|numeric|exists:App\Models\Department,id'
        ]);

        $exist = JobPosition::where('title',$request->title)
            ->where('department_id',$request->department_id)
            ->first();

        $exist && $this->error("Job Position already exists in this department!!");
        $data                  =  $request->all();
        $data['department_id'] =  $request->department_id;
        $job                   = new JobPosition($data);
        $job->save() || $this->error("Job title is not inserted!");

        $response=[
            'message' => 'Job added successfully',
            'job'     => $job
        ];
        return response($response,201);
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
        $job = JobPosition::find($id);
        $job || $this->error("Job Position not found!!");

        $this->validate($request, [
            'title' => 'sometimes|required',
            'department_id' =>'sometimes|numeric|required|exists:App\Models\Department,id'
        ]);
        $exist = JobPosition::where('title',$request->title)
            ->where('department_id',$request->department_id)
            ->first();

        $exist && $this->error("Job Position already exists in this department!!");
        $request->input('title')           &&  $job->title             = $request->input('title');
        $request->input('department_id')   &&  $job->department_id     = $request->input('department_id');
        $job->save() || $this->error("Job title is not inserted!");

        $response=[
            'message' => 'Job updated successfully',
            'job'     => $job
        ];
        return response($response,201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        $job = JobPosition::find($id);
        $job || $this->error("Job title doesnt exists !");
        $job->delete();
        return response(['message'=>'Job deleted successfully!!!'],200);
    }
}
