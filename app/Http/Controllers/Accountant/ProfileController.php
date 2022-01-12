<?php

namespace App\Http\Controllers\Accountant;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\JobPosition;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

#Models
use App\Models\Employee;
use App\Models\User;

class ProfileController extends Controller
{
    public function show()
    {
        $profile = Client::find(Auth::user()->client_id);
        $data = [
            'title'       => 'Profile',
            'user'        => Auth::user(),
            'profile'     => $profile,
        ];

        return view('accountant.profile.view', $data);
    }

    public function edit($id)
    {
        $employee = User::where('client_id',Auth::user()->client_id)
            ->where('employee_id',$id)
            ->with('employee')
            ->first();
        $positions = JobPosition::orderBy('id','DESC')->get();
        $data = [
            'title'        => 'Update Employee',
            'base_url'     => env('APP_URL', 'http://omnibiz.local'),
            'user'         => Auth::user(),
            'employee'     => $employee,
            'positions'    => $positions
        ];
        return view('accountant.employee.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $employee = User::where('client_id',Auth::user()->client_id)
            ->where('employee_id',$id)
            ->with('employee')
            ->first();

        $request->validate([
            'firstname'             => 'required|min:4',
            'lastname'              => 'required|min:4',
            'personal_email'        => "required|unique:App\Models\Employee,personal_email,{$id}",
            'password'              => 'present',
            'mobile_no'             => "required|digits:11|unique:App\Models\Employee,mobile_no,{$id}",
            'location'              => 'required',
            'bank_account_no'       => "required|unique:App\Models\Employee,bank_account_no,{$id}",
            'nationality'           => 'required',
            'nic_no'                => 'required|digits:13',
            'gender'                => 'required|in:female,male',
            'birth_date'            => 'required|date',
            'birth_place'           => 'required',
            'birth_country'         => 'required',
            'martial_status'        => 'required|in:single,married',
            'children'              => 'required',
            'emergency_contact'     => "required|numeric|digits:11|unique:App\Models\Employee,mobile_no,{$id}",
            'education_level'       => 'required|in:nothing,matriculation,intermediate,bachelor\'s,master\'s,doctorate',
            'education_field'       => 'present',
            'educational_institute' => 'present'
            # 'passport_no'           => 'present|digits:13',
            #'visa_number'           => 'present|digits:8',
            #'working_permit_number' => 'present|digits:8',
            #'visa_expire_date'      => 'present|date|after_or_equal:'.Carbon::now(),
        ],[
            'position_id.required' => 'The position field is required.'
        ]);

        $request->input('firstname')             &&      $employee->employee->firstname                 = $request->input('firstname');
        $request->input('lastname')              &&      $employee->employee->lastname                  = $request->input('lastname');
        $request->input('personal_email')        &&      $employee->employee->personal_email            = $request->input('personal_email');
        $request->input('picture')               &&      $employee->employee->picture                   = $request->input('picture');
        $request->input('mobile_no')             &&      $employee->employee->mobile_no                 = $request->input('mobile_no');
        $request->input('location')              &&      $employee->employee->location                  = $request->input('location');
        $request->input('bank_account_no')       &&      $employee->employee->bank_account_no           = $request->input('bank_account_no');
        $request->input('nationality')           &&      $employee->employee->nationality               = $request->input('nationality');
        $request->input('nic_no')                &&      $employee->employee->nic_no                    = $request->input('nic_no');
        $request->input('passport_no')           &&      $employee->employee->passport_no               = $request->input('passport_no');
        $request->input('birth_date')            &&      $employee->employee->birth_date                = Carbon::parse($request['birth_date'])->format('Y-m-d');
        $request->input('birth_place')           &&      $employee->employee->birth_place               = $request->input('birth_place');
        $request->input('birth_country')         &&      $employee->employee->birth_country             = $request->input('birth_country');
        $request->input('martial_status')        &&      $employee->employee->martial_status            = $request->input('martial_status');
        $request->input('children')              &&      $employee->employee->children                  = $request->input('children');
        $request->input('emergency_contact')     &&      $employee->employee->emergency_contact         = $request->input('emergency_contact');
        $request->input('visa_number')           &&      $employee->employee->visa_number               = $request->input('visa_number');
        $request->input('working_permit_number') &&      $employee->employee->working_permit_number     = $request->input('working_permit_number');
        $request->input('visa_expire_date')      &&      $employee->employee->visa_expire_date          = $request->input('visa_expire_date');
        $request->input('education_level')       &&      $employee->employee->education_level           = $request->input('education_level');
        $request->input('education_field')       &&      $employee->employee->education_field           = $request->input('education_field');
        $request->input('educational_institute') &&      $employee->employee->educational_institute     = $request->input('educational_institute');

        $employee->employee['updated_by']  = $id ;
        $employee->employee->save();

        $request->input ('password')   && $employee->password     = $request->input ('password');
        $employee['updated_by'] = $id ;
        $employee->save();

        return redirect(
            route('profile.employee',$id)
        )->with('success', 'Employee updated successfully!');
    }
}
