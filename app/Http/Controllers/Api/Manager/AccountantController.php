<?php

namespace App\Http\Controllers\Api\Manager;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AccountantController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        ($result = User::select('*')
            ->where('client_id', Auth::user()->client_id)
            ->where('user_role', 'accountant')
            ->with('employee')
            ->get()) || $this->error("Something went wrong");

        $result->count() || $this->error("Accountant do not exist");

        return response($result, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $todayDate = date('m/d/Y');
        $this->validate($request, [
            'firstname'             => 'required|min:4',
            'lastname'              => 'required|min:4',
            'personal_email'        => 'required|email|unique:App\Models\Employee',
            'picture'               => 'required',
            'username'              => 'required|unique:App\Models\User|min:4',
            'password'              => 'required',
            'email'                 => 'required|email|unique:App\Models\User',
            'mobile_no'             => 'required|numeric|digits:11|unique:App\Models\Employee',
            'position_id'           => 'required|numeric|exists:App\Models\JobPosition,id',
            'location'              => 'required',
            'working_hours'         => 'required|numeric',
            'working_time_start'    => 'required|date_format:H:i',
            'working_time_end'      => 'required|date_format:H:i',
            'break_time_start'      => 'required|date_format:H:i',
            'break_time_end'        => 'required|date_format:H:i',
            'salary'                => 'required|numeric',
            'bank_account_no'       => 'required|min:9|max:20',
            'nationality'           => 'required',
            'nic_no'                => 'required|digits:13',
            'gender'                => 'required|in:female,male',
            'passport_no'           => 'sometimes|required|digits:13',
            'birth_date'            => 'required|date',
            'birth_place'           => 'required',
            'birth_country'         => 'required',
            'martial_status'        => 'required|in:single,married',
            'children'              => 'sometimes|required',
            'emergency_contact'     => 'required|numeric|digits:11|unique:App\Models\Employee',
            'visa_number'           => 'sometimes|required|digits:8',
            'working_permit_number' => 'sometimes|required|digits:8',
            'visa_expire_date'      => 'sometimes|required|date|after_or_equal:'.$todayDate,
            'education_level'       => 'required|in:nothing,matriculation,intermediate,bachelor\'s,master\'s,doctorate',
            'education_field'       => 'sometimes|required',
            'educational_institute' => 'sometimes|required'
        ]);

        $data                 = $request->all();
        $data['income_tax']   = fetchSetting('incometax'); // Fetch gst from settings table
        $data['created_by']   = Auth::user()->employee_id;
        $data['client_id']    = Auth::user()->client_id;
        $data['position_id']  = $request->position_id;
        $employee             = new Employee($data);
        $employee->save() || $this->error("Accountant is not inserted!");

        $data               = $request->all();
        $data['password']   = Hash::make($request->password);
        $data['created_by'] = Auth::user()->employee_id;
        $data['client_id']  = Auth::user()->client_id;
        $data['employee_id']= $employee->id;
        $data['user_role']  = 'accountant';
        $user               = new User($data);
        $user->save() || $this->error("Accountant User is not inserted!");

        $response = [
            'message'  => 'Accountant added successfully',
            'employee' => $employee,
            'user'     => $user
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
        ($employee = Employee::find($id)) || $this->error('No Accountant found!');
        ($user = User::where('client_id',Auth::user()->client_id)
            ->where('employee_id',$id)
            ->first()) || $this->error ('No User found for Employee');
        $user->count() || $this->error("Accountant User do not exist");

        $todayDate = date('m/d/Y');
        $this->validate($request, [
            'firstname'             => 'sometimes|required|min:4',
            'lastname'              => 'sometimes|required|min:4',
            'personal_email'        => 'sometimes|required|email|unique:App\Models\Employee',
            'picture'               => 'sometimes|required',
            'username'              => 'sometimes|required|unique:App\Models\User|min:4',
            'password'              => 'sometimes|required',
            'email'                 => 'sometimes|required|email|unique:App\Models\User',
            'mobile_no'             => 'sometimes|required|numeric|digits:11|unique:App\Models\Employee',
            'position_id'           => 'sometimes|required|numeric|exists:App\Models\JobPosition,id',
            'location'              => 'sometimes|required',
            'working_hours'         => 'sometimes|required|numeric',
            'working_time_start'    => 'sometimes|required|date_format:H:i',
            'working_time_end'      => 'sometimes|required|date_format:H:i',
            'break_time_start'      => 'sometimes|required|date_format:H:i',
            'break_time_end'        => 'sometimes|required|date_format:H:i',
            'salary'                => 'sometimes|required|numeric',
            'bank_account_no'       => 'sometimes|required|min:9|max:20',
            'nationality'           => 'sometimes|required',
            'nic_no'                => 'sometimes|required|digits:13',
            'gender'                => 'sometimes|required|in:female,male',
            'passport_no'           => 'sometimes|required|digits:13',
            'birth_date'            => 'sometimes|required|date',
            'birth_place'           => 'sometimes|required',
            'birth_country'         => 'sometimes|required',
            'martial_status'        => 'sometimes|required|in:single,married',
            'children'              => 'sometimes|required',
            'emergency_contact'     => 'sometimes|required|numeric|digits:11|unique:App\Models\Employee',
            'visa_number'           => 'sometimes|required|digits:8',
            'working_permit_number' => 'sometimes|required|digits:8',
            'visa_expire_date'      => 'sometimes|required|date|after_or_equal:'.$todayDate,
            'education_level'       => 'sometimes|required|in:nothing,matriculation,intermediate,bachelor\'s,master\'s,doctorate',
            'education_field'       => 'sometimes|required',
            'educational_institute' => 'sometimes|required'
        ]);

        $request->input('firstname')             &&      $employee->firstname                 = $request->input('firstname');
        $request->input('lastname')              &&      $employee->lastname                  = $request->input('lastname');
        $request->input('personal_email')        &&      $employee->personal_email            = $request->input('personal_email');
        $request->input('picture')               &&      $employee->picture                   = $request->input('picture');
        $request->input('mobile_no')             &&      $employee->mobile_no                 = $request->input('mobile_no');
        $request->input('position_id')           &&      $employee->position_id               = $request->input('position_id');
        $request->input('location')              &&      $employee->location                  = $request->input('location');
        $request->input('working_hours')         &&      $employee->working_hours             = $request->input('working_hours');
        $request->input('working_time_start')    &&      $employee->working_time_start        = $request->input('working_time_start');
        $request->input('working_time_end')      &&      $employee->working_time_end          = $request->input('working_time_end');
        $request->input('break_time_start')      &&      $employee->break_time_start          = $request->input('break_time_start');
        $request->input('break_time_end')        &&      $employee->break_time_end            = $request->input('break_time_end');
        $request->input('salary')                &&      $employee->salary                    = $request->input('salary');
        $request->input('bank_account_no')       &&      $employee->bank_account_no           = $request->input('bank_account_no');
        $request->input('nationality')           &&      $employee->nationality               = $request->input('nationality');
        $request->input('nic_no')                &&      $employee->nic_no                    = $request->input('nic_no');
        $request->input('passport_no')           &&      $employee->passport_no               = $request->input('passport_no');
        $request->input('birth_date')            &&      $employee->birth_date                = $request->input('birth_date');
        $request->input('birth_place')           &&      $employee->birth_place               = $request->input('birth_place');
        $request->input('birth_country')         &&      $employee->birth_country             = $request->input('birth_country');
        $request->input('martial_status')        &&      $employee->martial_status            = $request->input('martial_status');
        $request->input('children')              &&      $employee->children                  = $request->input('children');
        $request->input('emergency_contact')     &&      $employee->emergency_contact         = $request->input('emergency_contact');
        $request->input('visa_number')           &&      $employee->visa_number               = $request->input('visa_number');
        $request->input('working_permit_number') &&      $employee->working_permit_number     = $request->input('working_permit_number');
        $request->input('visa_expire_date')      &&      $employee->visa_expire_date          = $request->input('visa_expire_date');
        $request->input('education_level')       &&      $employee->education_level           = $request->input('education_level');
        $request->input('education_field')       &&      $employee->education_field           = $request->input('education_field');
        $request->input('educational_institute') &&      $employee->educational_institute     = $request->input('educational_institute');

        $employee->updated_by = Auth::user()->employee_id;
        $employee->save() || $this->error ("Accountant is not updated");

        $request->input ('username')   && $user->username   = $request->input ('username');
        $request->input ('email')      && $user->email      = $request->input ('email');
        $user->updated_by = Auth::user()->employee_id;
        $user->save() || $this->error ("Accountant User is not updated");

        $response=[
            'message'  =>'Accountant updated successfully',
            'user'     => $user,
            'employee' => $employee
        ];
        return response($response,200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        $user = User::where('client_id',Auth::user()->client_id)
            ->where('employee_id',$id);
        $user->delete()|| $this->error("User of Accountant is not deleted");
        ($employee = Employee::find($id))
            ->delete() || $this->error("Accountant is not deleted");

        return response(['message' => 'Accountant deleted successfully'], 200);
    }
}
