<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BillingController extends Controller
{
    public function create()
    {
        $data = [
            'title' => 'Submit receipt',
            # Additional classes sent to view
            'class' => [
                'body' => 'register-page'
            ],
            'base_url' => env('APP_URL', 'http://omnibiz.local/')
        ];
        return view('billing.submit-receipt', $data);
    }

    public function awaitingApproval()
    {
        $data = [
            'title' => 'Awaiting approval from Admin'
        ];
        if (Auth::user()->client->active != 0) return redirect(route('dashboard.'.Auth::user()->user_role));
        return view('billing.pending-verification', $data);
    }

    public function unpaidClient()
    {
        $data = [
            'title' => 'Awaiting approval from Admin'
        ];
        if (Auth::user()->client->subscription[0]->next_payment_date >= Carbon::now()) return redirect(route('dashboard.'.Auth::user()->user_role));
        return view('billing.unpaid', $data);
    }
}
