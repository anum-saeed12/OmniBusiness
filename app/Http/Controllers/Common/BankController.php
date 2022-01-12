<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BankController extends Controller
{
    public function index()
    {
        $bank = Bank::paginate($this->count);
        $data = [
            'title'   => 'View Bank Statement',
            'user'    => Auth::user(),
            'banks'   => $bank,
        ];
        return view('bank.view',$data);
    }
    public function create()
    {

    }
    public function edit($id)
    {

    }
    public function store(Request $request)
    {

    }
    public function update(Request $request,$id)
    {

    }
    public function delete($id)
    {

    }
}
