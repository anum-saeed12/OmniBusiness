<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Mail\EmployeeMail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

#Models
use App\Models\Employee;
use App\Models\JobPosition;
use App\Models\User;
use App\Models\Client;
use App\Models\Invoice;
use App\Models\Purchase;
use App\Models\Quotation;
use App\Models\Sale;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class EmployeeController extends Controller
{
    public function index()
    {
        ($employees= User::select('users.*')->where('users.client_id', Auth::user()->client_id)
            ->join('employees','employees.id','=','users.employee_id')
            ->where('users.user_role', '!=', 'admin')
            ->where('users.user_role', '!=', 'client')
            ->whereNull('employees.deleted_at')
            ->orderBy('users.id','DESC')
            ->with('employee')
            ->paginate($this->count) );
        $data = [
            'title'       => 'View Employees',
            'user'        => Auth::user(),
            'employees'   => $employees,
        ];
        return view('employee.view', $data);
    }

    public function add()
    {
        $positions= JobPosition::orderBy('id','DESC')->get();
        $password = Str::random(8);
        $users    = User::select( DB::raw("CONCAT(clients.prefix , MAX(users.id + 1)) as _username"))
                           ->leftJoin('clients', 'clients.id', '=', 'users.client_id')
                           ->where('client_id', Auth::user()->client_id)
                           ->first();
        $data = [
            'title'    => 'Add Employee',
            'base_url' => env('APP_URL', 'http://omnibiz.local'),
            'user'     => Auth::user(),
            'positions'=> $positions,
            'users'    => $users,
            'password' => $password

        ];
        return view('employee.add', $data);
    }

    public function view($id)
    {
        # Checks if the employee exists
        if (!Employee::find($id)) return redirect(route('employee.list.client'))->with('error', 'Employee not found');
        $employee = User::where('client_id',Auth::user()->client_id)
            ->where('employee_id',$id)
            ->with('data')
            ->first();

        # Checks if the employee exists and the client owns the employee
        if (!$employee) return redirect(route('employee.list.client'))->with('error', 'Employee not found');

        # Fetches the progress data of the employee
        $employee->sales = Sale::select(DB::raw("COUNT(`id`) as total"))->where('employee_id', $employee->data->id)->where('client_id', Auth::user()->client_id)->first();
        $employee->invoices = Invoice::select(DB::raw("COUNT(`id`) as total"))->where('employee_id', $employee->data->id)->where('client_id', Auth::user()->client_id)->first();
        $employee->purchases = Purchase::select(DB::raw("COUNT(`id`) as total"))->where('employee_id', $employee->data->id)->where('client_id', Auth::user()->client_id)->first();
        $employee->quotations = Quotation::select(DB::raw("COUNT(`id`) as total"))->where('employee_id', $employee->data->id)->where('client_id', Auth::user()->client_id)->first();
        $today = Carbon::today()->format('Y-m-d');

        $sales = Sale::where('employee_id',$employee->data->id)->where('client_id', Auth::user()->client_id)->whereDate('created_at', $today )->with('items')->get();
        $purchases = Purchase::where('employee_id',$employee->data->id)->where('client_id', Auth::user()->client_id)->whereDate('created_at', $today )->with('items')->get();
        $quotations = Quotation::where('employee_id',$employee->data->id)->where('client_id', Auth::user()->client_id)->whereDate('created_at', $today )->with('items')->get();
        $invoices = Invoice::where('employee_id',$employee->data->id)->where('client_id', Auth::user()->client_id)->whereDate('created_at', $today )->with('items')->get();

        $positions = JobPosition::orderBy('id','DESC')->get();

        $user = Auth::user();
        //SALES
        $year_sales = Sale::select(
            DB::raw('SUM(total_amount) as total'),
            DB::raw('COUNT(*) as counter'),
            DB::raw("DATE_FORMAT(created_at,'%M, %Y') as creation_date")
        )
            ->where('created_at', '>=', Carbon::today()->firstOfYear())
            ->where('employee_id', $id)
            ->groupBy('creation_date')
            ->orderBy('created_at','ASC');
        $month_sales = Sale::select(
            DB::raw('SUM(total_amount) as total'),
            DB::raw('COUNT(*) as counter'),
            DB::raw("DATE_FORMAT(created_at,'%d %M, %Y') as creation_date")
        )->where('created_at', '>=', Carbon::today()->firstOfMonth())
            ->where('employee_id', $id)
            ->groupBy('creation_date')
            ->orderBy('created_at', 'ASC');
        $today_sales = Sale::select(
            'total_amount as total',
            'created_at as creation_date'
        )->where('created_at', '>=', Carbon::today())
            ->where('employee_id', $id);

        //PURCHASE
        $year_purchase = Purchase::select(
            DB::raw('SUM(total_amount) as total'),
            DB::raw('COUNT(*) as counter'),
            DB::raw("DATE_FORMAT(created_at,'%M, %Y') as creation_date")
        )
            ->where('created_at', '>=', Carbon::today()->firstOfYear())
            ->where('employee_id', $id)
            ->groupBy('creation_date')
            ->orderBy('created_at','ASC');

        $month_purchase = Purchase::select(
            DB::raw('SUM(total_amount) as total'),
            DB::raw('COUNT(*) as counter'),
            DB::raw("DATE_FORMAT(created_at,'%d %M, %Y') as creation_date")
        )->where('created_at', '>=', Carbon::today()->firstOfMonth())
            ->where('employee_id', $id)
            ->groupBy('creation_date')
            ->orderBy('created_at', 'ASC');
        $today_purchase = Purchase::select(
            'total_amount as total',
            'created_at as creation_date'
        )->where('created_at', '>=', Carbon::today())
            ->where('employee_id', $id);


        $data = [
            'title'                    => "{$employee->data->firstname} {$employee->data->lastname}",
            'base_url'                 => env('APP_URL', 'http://omnibiz.local'),
            'user'                     => Auth::user(),
            'employee'                 => $employee,
            'sales'                    => $sales,
            'purchases'                => $purchases,
            'invoices'                 => $invoices,
            'quotations'               => $quotations,
            'positions'                => $positions,
            'year_employee_sales'      => $year_sales->get(),
            'month_employee_sales'     => $month_sales->get(),
            'today_employee_sales'     => $today_sales->get(),
            'year_purchases'           => $year_purchase->get(),
            'month_purchases'          => $month_purchase->get(),
            'today_purchases'          => $today_purchase->get(),
            'currency'                 => 'PKR',
            'client'                   => Client::find($user->client_id),
            'gst'                      => fetchSetting('gst')

        ];
        return view('employee.employee', $data);
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
        return view('employee.edit', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'firstname'             => 'required',
            'lastname'              => 'required',
            'personal_email'        => 'required|email|unique:App\Models\Employee',
            'username'              => 'required|unique:App\Models\User',
            'password'              => 'required',
            'user_role'             => 'required|in:manager,accountant,employee',
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

        $data                       = $request->only('email','password','username','user_role');
        unset($data['_token']);
        $data['password']           = Hash::make($request->password);
        $data['username']           = $request->username;
        $data['created_by']         = Auth::user()->client_id;
        $data['client_id']          = Auth::user()->client_id;
        $data['employee_id']        = $employee->id;
        $data['user_role']          = $request->user_role;
        $user                       = new User($data);
        $user->save();

        $details = [
            'email'    => Auth::user()->email,
            'username' => $request->username,
            'password' => $request->password
        ];

        Mail::to($request->personal_email)->send(new EmployeeMail($details));

        return redirect(
            route('employee.mail.client')
        )->with('success', 'Employee added successfully!');
    }

    public function update(Request $request, $id)
    {
        $employee = User::where('client_id',Auth::user()->client_id)
            ->where('employee_id',$id)
            ->with('employee')
            ->first();

        $request->validate([
            'firstname'             => 'required',
            'lastname'              => 'required',
            'personal_email'        => "required|unique:App\Models\Employee,personal_email,{$id}",
            'username'              => "required|min:4|unique:App\Models\User,username,{$employee->id}",
            'password'              => 'present',
            'user_role'             => 'required|in:manager,accountant,employee',
            'email'                 => "required|min:4|unique:App\Models\User,email,{$employee->id}",
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
        ],[
            'position_id.required' => 'The position field is required.'
        ]);

        $request->input('firstname')             &&      $employee->employee->firstname                 = $request->input('firstname');
        $request->input('lastname')              &&      $employee->employee->lastname                  = $request->input('lastname');
        $request->input('personal_email')        &&      $employee->employee->personal_email            = $request->input('personal_email');
        $request->input('picture')               &&      $employee->employee->picture                   = $request->input('picture');
        $request->input('mobile_no')             &&      $employee->employee->mobile_no                 = $request->input('mobile_no');
        $request->input('position_id')           &&      $employee->employee->position_id               = $request->input('position_id');
        $request->input('location')              &&      $employee->employee->location                  = $request->input('location');
        $request->input('working_hours')         &&      $employee->employee->working_hours             = $request->input('working_hours');
        $request->input('working_time_start')    &&      $employee->employee->working_time_start        = Carbon::parse($request['working_time_start'])->format('H:i:s');
        $request->input('working_time_end')      &&      $employee->employee->working_time_end          = Carbon::parse($request['working_time_end'])->format('H:i:s');
        $request->input('break_time_start')      &&      $employee->employee->break_time_start          = Carbon::parse($request['break_time_start'])->format('H:i:s');
        $request->input('break_time_end')        &&      $employee->employee->break_time_end            = Carbon::parse($request['break_time_end'])->format('H:i:s');
        $request->input('salary')                &&      $employee->employee->salary                    = $request->input('salary');
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

        $employee->employee['updated_by'] = Auth::user()->client_id ;
        $employee->employee->save();

        $request->input ('username')   && $employee->username     = $request->input ('username');
        $request->input ('email')      && $employee->email        = $request->input ('email');
        $request->input ('password')   && $employee->password     = $request->input ('password');
        $request->input ('user_role')  && $employee->user_role    = $request->input ('user_role');
        $employee['updated_by'] = Auth::user()->client_id ;
        $employee->save();

        return redirect(
            route('employee.list.client')
        )->with('success', 'Employee updated successfully!');
    }

    public function delete($id)
    {
        $employee = Employee::find($id)->delete();

        $user = User::where('client_id',Auth::user()->client_id)
            ->where('employee_id',$id)->delete();
        return redirect(
            route('employee.list.client')
        )->with('success', 'Employee deleted successfully!');
    }

    public function sendemail(){
        return redirect(route('employee.list.client'))->with('success', 'Employee added successfully!');
    }
}
