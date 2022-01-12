<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Company;
use App\Models\Employee;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class   DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $data = [
            'class' => [
                'body' => ' sidebar-mini layout-fixed'
            ],
            'title' => 'Dashboard',
            'user' => $user
        ];
        $role = Auth::user()->user_role;
        return view("dashboard", $data);
    }

    public function admin()
    {
        $user = Auth::user();
        # Fetch User
        $total_user = User::select(DB::raw('COUNT(*) as total'))->first();

        # Fetch Client
        $client     = Client::select(DB::raw('COUNT(*) as total'))->first();

        $yearly_client = Client::select(
            DB::raw('COUNT(*) as total'),
            DB::raw("DATE_FORMAT(created_at,'%M, %Y') as creation_date"))
            ->where('created_at', '>=', Carbon::today()->firstOfYear())
            ->groupBy(DB::raw("DATE_FORMAT(created_at,'%M, %Y')"))
            ->orderBy('created_at','ASC');

        $monthly_client = Client::select(
            DB::raw('COUNT(*) as total'),
            DB::raw("DATE_FORMAT(created_at,'%d %M, %Y') as creation_date"))
            ->where('created_at', '>=', Carbon::today()->subWeek(7))
            ->groupBy(DB::raw("DATE_FORMAT(created_at,'%d %M, %Y')"))
            ->orderBy('created_at','ASC');

        $today_client = Client::select(
            DB::raw('COUNT(*) as total'),
            DB::raw("created_at as creation_date"))
            ->where('created_at', '>=', Carbon::today())->groupBy('created_at');

        $vendor = Company::select(DB::raw('COUNT(*) as total'));
        $data = [
            'class' => [
                'body' => ' sidebar-mini layout-fixed'
            ],
            'title'         => 'Dashboard',
            'user'          => $user,
            'total_user'    => $total_user,
            'client'        => $client,
            'currency'      => 'PKR',
            'year_clients'  => $yearly_client->get(),
            'month_clients' => $monthly_client->get(),
            'today_clients' => $today_client->get(),
            'vendor'        => $vendor->first()
        ];
        return view("admin.dashboard", $data);
    }

    public function client()
    {
        $user = Auth::user();
        # Fetch sales
        $sales = Sale::select(DB::raw('SUM(total_amount) as total'))
            #->where('created_at', '>=', Carbon::today()->subDays(3))
            ->where('client_id', $user->client_id)
            ->first();
        # Fetch purchases
        $purchases = Purchase::select(DB::raw('SUM(total_amount) as total'))
            #->where('created_at', '>=', Carbon::today()->subDays(3))
            ->where('client_id', $user->client_id)
            ->first();
        # Fetch employees
        $employees = Employee::select(DB::raw('COUNT(id) as total'))
            ->where('client_id', $user->client_id)
            ->first();
        # Fetch all products
        $products = Product::select(DB::raw('COUNT(id) as total'))
            ->where('client_id', $user->client_id)
            ->first();

        $year_sales = Sale::select(
            DB::raw('SUM(total_amount) as total'),
            DB::raw('COUNT(*) as counter'),
            DB::raw("DATE_FORMAT(created_at,'%M, %Y') as creation_date")
        )
            ->where('created_at', '>=', Carbon::today()->firstOfYear())
            ->where('client_id', $user->client_id)
            ->groupBy('creation_date')
            ->orderBy('created_at','ASC');
        $year_purchases = Purchase::select(
            DB::raw('SUM(total_amount) as total'),
            DB::raw('COUNT(*) as counter'),
            DB::raw("DATE_FORMAT(created_at,'%M, %Y') as creation_date")
        )
            ->where('created_at', '>=', Carbon::today()->firstOfYear())
            ->where('client_id', $user->client_id)
            ->groupBy('creation_date')
            ->orderBy('created_at','ASC');

        $data = [
            'class' => [
                'body' => ' sidebar-mini layout-fixed'
            ],
            'title' => 'Dashboard',
            'user' => $user,
            'sales' => $sales,
            'purchases' => $purchases,
            'employees' => $employees,
            'products' => $products,
            'year_sales' => $year_sales->get(),
            'year_purchases' => $year_purchases->get(),
            'currency' => 'PKR'
        ];
        return view("client.dashboard", $data);
    }

    public function manager()
    {
        $user = Auth::user();
        # Fetch sales
        $sales = Sale::select(DB::raw('SUM(total_amount) as total'))
            #->where('created_at', '>=', Carbon::today()->subDays(3))
            ->where('client_id', $user->client_id)
            ->first();
        # Fetch purchases
        $purchases = Purchase::select(DB::raw('SUM(total_amount) as total'))
            #->where('created_at', '>=', Carbon::today()->subDays(3))
            ->where('client_id', $user->client_id)
            ->first();
        $employees = Employee::select(DB::raw('COUNT(id) as total'))
            ->where('client_id', $user->client_id)
            ->first();
        $data = [
            'class' => [
                'body' => ' sidebar-mini layout-fixed'
            ],
            'title' => 'Dashboard',
            'user' => $user,
            'sales' => $sales,
            'purchases' => $purchases,
            'employees' => $employees,
            'currency' => 'PKR'
        ];
        return view("manager.dashboard", $data);
    }

    public function employee()
    {
        $user = Auth::user();
        # Fetch sales
        $sales = Sale::select(DB::raw('SUM(total_amount) as total'))
            #->where('created_at', '>=', Carbon::today()->subDays(3))
            ->where('client_id', $user->client_id)
            ->where('employee_id', $user->employee_id)
            ->first();
        # Fetch purchases
        $purchases = Purchase::select(DB::raw('SUM(total_amount) as total'))
            #->where('created_at', '>=', Carbon::today()->subDays(3))
            ->where('client_id', $user->client_id)
            ->where('employee_id', $user->employee_id)
            ->first();
        $data = [
            'class' => [
                'body' => ' sidebar-mini layout-fixed'
            ],
            'title' => 'Dashboard',
            'user' => $user,
            'sales' => $sales,
            'purchases' => $purchases,
            'currency' => 'PKR'
        ];
        return view("employee.dashboard", $data);
    }

    public function accountant()
    {
        $user = Auth::user();
        # Fetch sales
        $sales = Sale::select(DB::raw('SUM(total_amount) as total'))
            #->where('created_at', '>=', Carbon::today()->subDays(3))
            ->where('client_id', $user->client_id)
            ->first();
        # Fetch purchases
        $purchases = Purchase::select(DB::raw('SUM(total_amount) as total'))
            #->where('created_at', '>=', Carbon::today()->subDays(3))
            ->where('client_id', $user->client_id)
            ->first();
        $employees = Employee::select(DB::raw('COUNT(id) as total'))
            ->where('client_id', $user->client_id)
            ->first();
        $data = [
            'class' => [
                'body' => ' sidebar-mini layout-fixed'
            ],
            'title'     => 'Dashboard',
            'user'      => $user,
            'sales'     => $sales,
            'purchases' => $purchases,
            'employees' => $employees,
            'currency'  => 'PKR'
        ];
        return view("accountant.dashboard", $data);
    }
}
