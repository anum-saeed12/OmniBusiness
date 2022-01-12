<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Ramsey\Uuid\Uuid;

class SubscriptionController extends Controller
{
    public function index()
    {
        $my_subscriptions = Subscription::where('client_id', Auth::user()->client_id);
        $data = [
            'title' => 'My Membership'
        ];
        return view('clients.subscription.view');
    }

    public function add()
    {
        return view('clients.subscription.add');
    }

    public function upload(Request $request)
    {
        $client = Client::find(Auth::user()->client_id);

        $subscription                    = new Subscription();
        $subscription->client_id         = Auth::user()->client_id;
        $subscription->next_payment_date = $client->type_of_subscription == 'yearly'?Carbon::today()->addYear():Carbon::today()->addMonth();
        $subscription->last_payment_date = Carbon::today();
        $subscription->receipt           = $this->uploadReceipt($request->file('receipt'));
        $subscribed = $subscription->save();

        return redirect()->back()->with('success', 'Receipt has been uploaded');
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
}
