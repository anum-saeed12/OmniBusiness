<?php

namespace App\Http\Controllers\Api\Employee;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        ($employee = Employee::find(Auth::user()->employee_id)) || $this->error('No record found!');
        $response = [
            'user'   => Auth::user(),
            'employee' => $employee
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
    public function update(Request $request)
    {
        ($user = User::find(Auth::id())) || $this->error("User not found");
        ($employee=Employee::find(Auth::user()->employee_id))|| $this->error("Employee not found");

        $todayDate = date('m/d/Y');
        $this->validate($request, [
            'firstname'             => 'sometimes|required|min:4',
            'lastname'              => 'sometimes|required|min:4',
            'personal_email'        => 'sometimes|required|email|unique:App\Models\Employee',
            'picture'               => 'sometimes|required',
            'password'              => 'sometimes|required',
            'email'                 => 'sometimes|required|email|unique:App\Models\User',
            'mobile_no'             => 'sometimes|required|numeric|digits:11|unique:App\Models\Employee',
            'location'              => 'sometimes|required',
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
        $request->input('location')              &&      $employee->location                  = $request->input('location');
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

        $employee->updated_by  = Auth::user()->employee_id;
        $employee->save() || $this->error ("Employee is not updated!");

        $request->input('password')  &&  $user->password  = Hash::make($request->input('password'));
        $request->input('email')      && $user->email    = $request->input('email');

        $user->updated_by = Auth::user()->employee_id;
        $user->save() || $this->error ("Employee User is not updated");

        $response=[
            'message'  =>'User updated successfully',
            'user'     => $user,
            'employee' => $employee
        ];
        return response($response,200);
    }


}
