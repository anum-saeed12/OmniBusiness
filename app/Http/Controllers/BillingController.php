<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
        return view('billing.pending-verification', $data);
    }

    public function unpaidClient()
    {
        $data = [
            'title' => 'Awaiting approval from Admin'
        ];
        return view('billing.unpaid', $data);
    }
}
