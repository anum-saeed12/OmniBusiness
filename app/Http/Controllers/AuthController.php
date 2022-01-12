<?php
/**
 * @author Anum Saeed <anumsaeed286@gmail.com>
 *
 * This controller manages the user authentication
 *
 * We have not used any default scaffolding provided
 * by Laravel. This is a custom built controller.
 */
namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;

/**
 * This controller manages the user authentication
 *
 * We have not used any default scaffolding provided
 * by Laravel. This is a custom built controller.
 *
 */
class AuthController extends Controller
{
    # This will tell the login function to redirect the user type to their homepage
    private $homepage = [
        # Middleware    =>     Route name
        'admin'         => 'dashboard.admin',
        'client'        => 'dashboard.client',
        'manager'       => 'dashboard.manager',
        'accountant'    => 'dashboard.accountant',
        'employee'      => 'dashboard.employee',
    ];
    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     * @url /login
     */
    public function login()
    {
        # Additional data sent to view
        $data = [
            # Additional classes sent to view
            'class' => [
                'body' => 'login-page'
            ],
            'base_url' => env('APP_URL', 'http://omnibiz.local/'),
            'title' => 'Login'
        ];
        return view('auth.login', $data);
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     * @url /register
     */
    public function register_step_1()
    {
        # Additional data sent to view
        $data = [
            # Additional classes sent to view
            'class' => [
                'body' => 'register-page'
            ],
            'base_url' => env('APP_URL', 'http://omnibiz.local/'),
            'title' => 'Registration'
        ];
        return view('auth.register', $data);
    }

    public function register_step_2(Request $request)
    {
        $request->validate([
            'company'   => 'required|min:2',
            'email'     => 'required|email',
            'password'  => 'required|confirmed|min:4',
            'registrar' => 'required|in:employee,owner'
        ]);

        # Prepare additional data to send to the view
        $data = [
            # Additional classes sent to view
            'class' => [
                'body' => 'register-page'
            ],
            'base_url' => env('APP_URL', 'http://omnibiz.local/'),
            'title'    => 'Registration',
            'request'  => $request->all()
        ];

        # Check the registrar
        if ($request->registrar == 'employee')
            return view('auth.employee', $data);

        return view('auth.employee', $data);
    }

    /**
     * @param Request $request
     * @url /login (POST)
     */
    public function attemptLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        # Checks if the user exists
        $user = User::where('email', $request->email)->first();

        # Checks if the user does not exist
        if (!$user) return back()->with('error', 'User not found!');

        # Checks if the password is correct or not
        if (!Hash::check($request->password, $user->password)) return back()->with('error', 'Invalid credentials!');

        # Generate a new session for the user
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $request->input('remember', false))) {
            $request->session()->regenerate();

            return redirect()->intended(route($this->homepage[$user->user_role]));
        }

        return redirect(route($this->homepage[$user->user_role]));
    }

    /**
     * @param Request $request
     * @url /register (POST)
     */
    public function newRegistration(Request $request)
    {
        $request->validate([
            'name'                 => 'required|min:2',
            'official_email'       => 'required|email|unique:App\Models\User,email',
            'password'             => 'required|confirmed|min:4',
            'type_of_subscription' => 'required|in:yearly,monthly'
        ]);

        # Insert client in the database
        $new_client = new Client();
        $new_client->official_email = $request->official_email;
        $new_client->name = $request->name;
        # Setting default values
        $new_client->ntn_number = '';
        $new_client->avatar = '';
        $new_client->license = '';
        $new_client->website = '';
        $new_client->overview = '';

        $saved = $new_client->save();

        # Checks if client was inserted in the database
        if (!$saved)
            return redirect()->back()->with('error', 'Company not registered!');

        $client_id = $new_client->id;

        # Insert user in the database
        $new_user = new User();
        $new_user->client_id = $client_id;
        $new_user->email     = $request->official_email;
        $new_user->username  = $request->name .Str::random(5);
        $new_user->password  = Hash::make($request->password);
        $new_user->user_role = 'client';
        $new_user->created_by = 0;

        $saved = $new_user->save();

        # Subscription Plan Details

        $subscription = new Subscription();
        $subscription->client_id = $client_id;
        $subscription->next_payment_date = $request->type_of_subscription == 'yearly'?Carbon::today()->addYear():Carbon::today()->addMonth();
        $subscription->last_payment_date = Carbon::today();
        $subscription->type_of_subscription = $request->type_of_subscription;
        $subscription->receipt = $this->uploadReceipt($request->file('receipt'));
        $subscription->approved = 2;
        $subscribed = $subscription->save();

        $data = [
            'title' => 'Pending'
        ];
        # If everything went well
        return view('billing.pending-verification',$data);
    }

    # This function uploads the receipt file to the server
    private function uploadReceipt($file)
    {
        $filename = Uuid::uuid4().".{$file->extension()}";
        $private_path = $file->storeAs(
            'public/receipts', $filename
        );
        $public_path = Storage::url("receipts/$filename");
        return $public_path;
    }

    public function logout()
    {
        Auth::logout();
        return redirect(route('login'));
    }
    public function password()
    {
        $data = [
            'title' => 'Forgot Password',
            'base_url' => env('APP_URL', 'http://omnibiz.local/'),
        ];
        # If everything went well
        return view('auth.forgot-password',$data);
    }

   public function forgotpassword(Request $request)
   {
       $user = Auth::user()->id;
       $request->validate([
            'password'             => 'required|confirmed|min:4'
        ]);

       $request->input('password')   &&  $user->password      = Hash::make($request->input('password'));
       $user->save();

        return redirect(route('login'));
    }


}
