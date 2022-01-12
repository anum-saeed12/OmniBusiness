<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Models\ClientDepartment;
use App\Models\Department;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use phpDocumentor\Reflection\Types\Null_;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $department= ClientDepartment::where('client_id',Auth::user()->client_id)
            ->with('department')
            ->get();
        $department->count() || $this->error("No department available");

        $response=['Department' => $department];

        return response($response, 200);
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
            'name' => 'required|exists:App\Models\Department,name'
        ]);

        $exist = Department::where('name',$request->name)->first();

        if(empty($exist))
        {
            $data               = $request->all();
            $department         = new Department($data);
            $department->save() || $this->error("Department is not inserted!");

            $data                   = $request->all();
            $data['client_id']      = Auth::user()->client_id;
            $data['department_id']  = $department->id;
            $client                 = new ClientDepartment($data);
            $client->save() || $this->error("Client-Department is not inserted!");
            $response = [
                'Message' => 'Department is added',
                'Department' => $department->id,
                'Client' => $client
            ];
            return response($response, 200);

        }

        $result = ClientDepartment::where('client_id',Auth::user()->client_id)
               ->where('department_id',$exist->id)->first();
        if(empty($result)) {


            $data                  = $request->all();
            $data['client_id']     = Auth::user()->client_id;
            $data['department_id'] = $exist->id;
            $client                = new ClientDepartment($data);
            $client->save()       || $this->error("Client-Department is not inserted!");

            $response = [
                'Message' => 'Department is added',
                'Department' => $exist->id,
                'Client' => $client
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
        //Meray khaiyal sy update client ky pass nhi hona chaiya q ky ager usnay
        //kisi department ka name update kar dya to hamary jitny bhi client hongay
        //sbky pass wo update hojay ga name.

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        $result= ClientDepartment::where('client_id',Auth::user()->client_id)
                 ->where('department_id',$id)
                 ;
        $result->delete() || $this->error("Department is not available!");

        return response(['message' => 'Department deleted successfully'], 200);
    }
}
