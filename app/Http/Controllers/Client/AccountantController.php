<?php

namespace App\Http\Controllers\Client;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;

#models
use App\Models\Employee;
use App\Models\JobPosition;
use App\Models\User;

class AccountantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        ($accountants= User::where('client_id', Auth::user()->client_id)
                        ->where('user_role', 'accountant')
                        ->orderBy('id','DESC')
                        ->with('employee')
                        ->paginate($this->count) );

        $data = [
            'title'       => 'View Accountants',
            'user'        => Auth::user(),
            'accountants' => $accountants,
        ];

        return view('client.accountant.view', $data);
    }

    public function add()
    {
        $positions= JobPosition::orderBy('id','DESC')->get();
        $data = [
            'title'    => 'Add Accountant',
            'base_url' => env('APP_URL', 'http://omnibiz.local'),
            'user'     => Auth::user(),
            'positions'=> $positions
        ];
        return view('client.accountant.add', $data);
    }

    public function edit($id)
    {
        $accountant = User::where('client_id',Auth::user()->client_id)
            ->where('employee_id',$id)
            ->with('employee')
            ->first();
        $positions = JobPosition::orderBy('id','DESC')->get();
        $data = [
            'title'        => 'Update Accountant',
            'base_url'     => env('APP_URL', 'http://omnibiz.local'),
            'user'         => Auth::user(),
            'accountant'   => $accountant,
            'positions'    => $positions
        ];
        return view('client.accountant.edit', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'firstname'             => 'required|min:4',
            'lastname'              => 'required|min:4',
            'personal_email'        => 'required|email|unique:App\Models\Employee',
            'username'              => 'required|unique:App\Models\User|min:4',
            'password'              => 'required',
            'email'                 => 'required|email|unique:App\Models\User',
            'mobile_no'             => 'required|numeric|digits:11|unique:App\Models\Employee',
            'position_id'           => 'required|numeric|exists:App\Models\JobPosition,id',
            'location'              => 'required',
            'working_hours'         => 'required|numeric',
            'working_time_start'    => 'required',
            'working_time_end'      => 'required',
            'break_time_start'      => 'required',
            'break_time_end'        => 'required',
            'salary'                => 'required|numeric',
            'bank_account_no'       => 'required|min:9|max:20',
            'nationality'           => 'required',
            'nic_no'                => 'required|digits:13',
            'gender'                => 'required|in:female,male',
            'birth_date'            => 'required|date',
            'birth_place'           => 'required',
            'birth_country'         => 'required',
            'martial_status'        => 'required|in:single,married',
            'children'              => 'present',
            'emergency_contact'     => 'required|digits:11|unique:App\Models\Employee',
            'education_level'       => 'required|in:nothing,matriculation,intermediate,bachelor\'s,master\'s,doctorate',
            'education_field'       => 'present',
            'educational_institute' => 'present'
            # 'passport_no'           => 'present|digits:13',
            #'visa_number'           => 'present|digits:8',
            #'working_permit_number' => 'present|digits:8',
            #'visa_expire_date'      => 'present|date|after_or_equal:'.Carbon::now(),
        ],
            [
                'position_id.required' => 'The position field is required.'
            ]);
        $data                 = $request->all();
        unset($data['_token']);
        $data['working_time_start'] = Carbon::parse($data['working_time_start'])->format('H:i:s');
        $data['working_time_end']   = Carbon::parse($data['working_time_end'])->format('H:i:s');
        $data['break_time_start']   = Carbon::parse($data['break_time_start'])->format('H:i:s');
        $data['break_time_end']     = Carbon::parse($data['break_time_end'])->format('H:i:s');
        $data['birth_date']         = Carbon::parse($data['birth_date'])->format('Y-m-d');
        $data['income_tax']         = fetchSetting('incometax'); // Fetch gst from settings table
        $data['created_by']         = Auth::user()->client_id;
        $data['client_id']          = Auth::user()->client_id;
        $data['position_id']        = $request->position_id;
        $employee                   = new Employee($data);
        $employee->save();

        $data                       = $request->only('email','password','username');
        unset($data['_token']);
        $data['password']           = Hash::make($request->password);
        $data['created_by']         = Auth::user()->client_id;
        $data['client_id']          = Auth::user()->client_id;
        $data['employee_id']        = $employee->id;
        $data['user_role']          = 'accountant';
        $user                       = new User($data);
        $user->save();

        return redirect(
            route('accountant.list.client')
        )->with('success', 'Accountant added successfully!');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $accountant = User::where('client_id',Auth::user()->client_id)
            ->where('employee_id',$id)
            ->with('employee')
            ->first();

        $request->validate([
            'firstname'             => 'required|min:4',
            'lastname'              => 'required|min:4',
            'personal_email'        => "required|unique:App\Models\Employee,personal_email,{$id}",
            'username'              => "required|min:4|unique:App\Models\User,username,{$accountant->id}",
            'password'              => 'present',
            'email'                 => "required|min:4|unique:App\Models\User,email,{$accountant->id}",
            'mobile_no'             => "required|digits:11|unique:App\Models\Employee,mobile_no,{$id}",
            'position_id'           => 'required|exists:App\Models\JobPosition,id',
            'location'              => 'required',
            'working_hours'         => 'required|numeric',
            'working_time_start'    => 'required',
            'working_time_end'      => 'required',
            'break_time_start'      => 'required',
            'break_time_end'        => 'required',
            'salary'                => 'required|numeric',
            'bank_account_no'       => "required|min:9|max:20|unique:App\Models\Employee,bank_account_no,{$id}",
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
        ],
            [
            'position_id.required' => 'The position field is required.'
        ]);

        $request->input('firstname')             &&      $accountant->employee->firstname                 = $request->input('firstname');
        $request->input('lastname')              &&      $accountant->employee->lastname                  = $request->input('lastname');
        $request->input('personal_email')        &&      $accountant->employee->personal_email            = $request->input('personal_email');
        $request->input('picture')               &&      $accountant->employee->picture                   = $request->input('picture');
        $request->input('mobile_no')             &&      $accountant->employee->mobile_no                 = $request->input('mobile_no');
        $request->input('position_id')           &&      $accountant->employee->position_id               = $request->input('position_id');
        $request->input('location')              &&      $accountant->employee->location                  = $request->input('location');
        $request->input('working_hours')         &&      $accountant->employee->working_hours             = $request->input('working_hours');
        $request->input('working_time_start')    &&      $accountant->employee->working_time_start        = Carbon::parse($request['working_time_start'])->format('H:i:s');
        $request->input('working_time_end')      &&      $accountant->employee->working_time_end          = Carbon::parse($request['working_time_end'])->format('H:i:s');
        $request->input('break_time_start')      &&      $accountant->employee->break_time_start          = Carbon::parse($request['break_time_start'])->format('H:i:s');
        $request->input('break_time_end')        &&      $accountant->employee->break_time_end            = Carbon::parse($request['break_time_end'])->format('H:i:s');
        $request->input('salary')                &&      $accountant->employee->salary                    = $request->input('salary');
        $request->input('bank_account_no')       &&      $accountant->employee->bank_account_no           = $request->input('bank_account_no');
        $request->input('nationality')           &&      $accountant->employee->nationality               = $request->input('nationality');
        $request->input('nic_no')                &&      $accountant->employee->nic_no                    = $request->input('nic_no');
        $request->input('passport_no')           &&      $accountant->employee->passport_no               = $request->input('passport_no');
        $request->input('birth_date')            &&      $accountant->employee->birth_date                = Carbon::parse($request['birth_date'])->format('Y-m-d');
        $request->input('birth_place')           &&      $accountant->employee->birth_place               = $request->input('birth_place');
        $request->input('birth_country')         &&      $accountant->employee->birth_country             = $request->input('birth_country');
        $request->input('martial_status')        &&      $accountant->employee->martial_status            = $request->input('martial_status');
        $request->input('children')              &&      $accountant->employee->children                  = $request->input('children');
        $request->input('emergency_contact')     &&      $accountant->employee->emergency_contact         = $request->input('emergency_contact');
        $request->input('visa_number')           &&      $accountant->employee->visa_number               = $request->input('visa_number');
        $request->input('working_permit_number') &&      $accountant->employee->working_permit_number     = $request->input('working_permit_number');
        $request->input('visa_expire_date')      &&      $accountant->employee->visa_expire_date          = $request->input('visa_expire_date');
        $request->input('education_level')       &&      $accountant->employee->education_level           = $request->input('education_level');
        $request->input('education_field')       &&      $accountant->employee->education_field           = $request->input('education_field');
        $request->input('educational_institute') &&      $accountant->employee->educational_institute     = $request->input('educational_institute');

        $accountant->updated_by  = Auth::user()->client_id;
        $accountant->employee->save();

        $request->input ('username')   && $accountant->username     = $request->input ('username');
        $request->input ('email')      && $accountant->email        = $request->input ('email');
        $request->input ('password')   && $accountant->password        = $request->input ('password');
        $request->input ('user_role')  && $accountant->user_role    = $request->input ('user_role');
        $accountant->updated_by = Auth::user()->client_id;
        $accountant->save();

        return redirect(
            route('accountant.list.client')
        )->with('success', 'Accountant updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete($id)
    {
        $user = User::where('client_id',Auth::user()->client_id)
            ->where('employee_id',$id)->where('user_role','accountant')->delete();
        $employee = Employee::find($id)->delete();

        return redirect(
            route('accountant.list.client')
        )->with('success', 'Accountant deleted successfully!');
    }
}
