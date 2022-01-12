<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public $lan = 'en';
    public $count = 10;
    public $page = 1;
    public $from = 0;

    public $search;
    public $all;

    public $user_term;
    public $worker_term;

    public $multi_order;

    public $employee_id;

    public function __construct(Request $request)
    {

        $this->user_term = config('app.term.user');
        $this->worker_term = config('app.term.worker');
        # Allow workers to accept multiple orders
        $this->multi_order = config('app.multi_orders');

        $lan = $request->header('lan', 'en');
        if (preg_match('/zh/i', $lan)) {
            $this->lan = 'zh';
        } else {
            $this->lan = 'en';
        }

        $this->page = $request->input('page', 1);
        if (!is_numeric($this->page) || $this->page < 1) {
            $this->page = 1;
        }
        $this->employee_id = $request->input('employee', false);
        if (!is_numeric($this->employee_id) || $this->employee_id < 1) {
            $this->employee_id = false;
        }
        # API can change the count of the page
        $this->count = $request->input('count', 15);
        if ($this->page > 1) {
            $this->from = ($this->page - 1) * $this->count;
        }

        # Check if 'all' filter is defined
        $this->all = $request->input('all', 1);

        # Search keyword
        $this->search = $request->input('q', false);
    }

    public function error($message)
    {
        header('Content-Type:application/json');
        header('Access-Control-Allow-Origin:*');
        header('Access-Control-Allow-Headers:*');
        header('Access-Control-Allow-Headers:*');
        echo json_encode(['status' => 0, 'message' => $message]);
        exit;
    }

    public function validate(Request $request, array $rules, array $messages = [], array $customAttributes = []): bool
    {
        array_walk($rules, function (&$value) {
            !is_string($value) || strstr($value, 'bail') || $value = 'bail|' . $value;
        });
        $data = $data = $request->all();

        $validator = Validator::make($data, $rules, $messages, $customAttributes);
        if ($validator->fails()) {
            $error = $validator->errors()->first();
            header('Content-Type:application/json');
            header('Access-Control-Allow-Origin:*');
            header('Access-Control-Allow-Headers:*');
            header('Access-Control-Allow-Headers:*');
            echo json_encode(['status' => 0, 'message' => $error]);
            exit;
        }
        return true;
    }
}
