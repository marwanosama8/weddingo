<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use App\Models\UniqueId;
use App\Models\User;
use Illuminate\Http\Request;
use BaklySystems\PayMob\Facades\PayMob;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PayMobController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display checkout page.
     *
     * @param  int  $orderId
     * @return Response
     */
    public function checkingOut($integration_id, $subscription_id)
    {
        // $this->validate($request, [
        //     'integration_id' => 'required',
        //     'orderId' => 'required',
        // ]);
        $uniqueId       = config('paymob.order.model', 'App\Subscription')::find($subscription_id)->uniqueId()->latest()->first();
        # code... get order user.
        $auth        = PayMob::authPaymob(); // login PayMob servers
        if (property_exists($auth, 'detail')) { // login to PayMob attempt failed.
            return 'paymob auth failed';
        }

        $subscription = Subscription::find($subscription_id);
        $paymobOrder = PayMob::makeOrderPaymob( // make order on PayMob
            $auth->token,
            $auth->profile->id,
            $subscription->totalCost * 100,
            $uniqueId->unique_id
        );

        UniqueId::create([
            'subscription_id' => $subscription_id,
            'unique_id' => $subscription_id . '_' . time() . '_' . Str::random(6),
        ]);
        // Duplicate order id
        // PayMob saves your order id as a unique id as well as their id as a primary key, thus your order id must not
        // duplicate in their database. 
        if (isset($paymobOrder->message)) {
            if ($paymobOrder->message == 'duplicate') {
                return 'your order is been in databse';
            }
        }
        $user = auth()->user();
        // $uniqueId->update(['paymob_order_id' => $paymobOrder->id]); // save paymob order id for later usage.
        $payment_key = PayMob::getPaymentKeyPaymob( // get payment key
            $integration_id,
            $auth->token,
            $subscription->totalCost * 100,
            $paymobOrder->id,
            // For billing data
            $user->email, // optional
            $user->first_name, // optional
            $user->last_name, // optional
            $user->phone, // optional
        );

        return redirect('https://accept.paymob.com/api/acceptance/iframes/738692?payment_token=' . $payment_key->token);
    }

    /**
     * Make payment on PayMob for API (mobile clients).
     * For PCI DSS Complaint Clients Only.
     *
     * @param  \Illuminate\Http\Reuqest  $request
     * @return Response
     */
    public function payAPI(Request $request)
    {
        $this->validate($request, [
            'orderId'         => 'required|integer',
            'card_number'     => 'required|numeric|digits:16',
            'card_holdername' => 'required|string|max:255',
            'card_expiry_mm'  => 'required|integer|max:12',
            'card_expiry_yy'  => 'required|integer',
            'card_cvn'        => 'required|integer|digits:3',
        ]);

        $user    = auth()->user();
        $order   = config('paymob.order.model', 'App\Order')::findOrFail($request->orderId);
        $payment = PayMob::makePayment( // make transaction on Paymob servers.
            $payment_key_token,
            $request->card_number,
            $request->card_holdername,
            $request->card_expiry_mm,
            $request->card_expiry_yy,
            $request->card_cvn,
            $order->paymob_order_id,
            $user->firstname,
            $user->lastname,
            $user->email,
            $user->phone
        );

        # code...
    }

    /**
     * Transaction succeeded.
     *
     * @param  object  $order
     * @return void
     */
    protected function succeeded($order, $trans_number, $payment_method)
    {
        $user = auth()->user();
        $subs = $order;

        $store = DB::table('user_subscriptions')->insert([
            'user_id' => $user->id,
            'subsciption_id' => $subs->id,
            'payment_method' => $payment_method,
            'total_price' => $subs->totalCost,
            'transaction_number' => $trans_number,
        ]);
        // giving partner his gallery limit points
        $parnter = $user->partner->update([
            'gallery_limit' => $subs->blog_per_month
        ]);

       DB::table('subscribtion_unique_id')->insert([
            'subscription_id' => $subs->id,
            'unique_id' => $subs->id . '_' . time() . '_' . Str::random(6),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        return view('payment.successfully');
    }

    /**
     * Transaction voided.
     *
     * @param  object  $order
     * @return void
     */
    protected function voided($order)
    {
        return view('payment.faild');
    }

    /**
     * Transaction refunded.
     *
     * @param  object  $order
     * @return void
     */
    protected function refunded($order)
    {
        return view('payment.failed');
    }

    /**
     * Transaction failed.
     *
     * @param  object  $order
     * @return void
     */
    protected function failed($order)
    {
        return view('payment.failed');
    }

    /**
     * Processed callback from PayMob servers.
     * Save the route for this method in PayMob dashboard >> processed callback route.
     *
     * @param  \Illumiante\Http\Request  $request
     * @return  Response
     */
    public function processedCallback(Request $request)
    {
        // $orderId = filter_var($request['merchant_order_id'], FILTER_VALIDATE_INT);
        // dd($request['merchant_order_id']);
        $order   = UniqueId::where('unique_id',$request['merchant_order_id'])->first()->subscription;

        // Statuses.
        $isSuccess  = filter_var($request['success'], FILTER_VALIDATE_BOOLEAN);
        $isVoided  = filter_var($request['is_voided'], FILTER_VALIDATE_BOOLEAN);
        $isRefunded  = filter_var($request['is_refunded'], FILTER_VALIDATE_BOOLEAN);

        if ($isSuccess && !$isVoided && !$isRefunded) { // transcation succeeded.
            return   $this->succeeded($order, $request['id'], $request['source_data_sub_type']);
        } elseif ($isSuccess && $isVoided) { // transaction voided.
            return  $this->voided($order);
        } elseif ($isSuccess && $isRefunded) { // transaction refunded.
            return $this->refunded($order);
        } elseif (!$isSuccess) { // transaction failed.
            return  $this->failed($order);
        }
    }

    /**
     * Display invoice page (PayMob response callback).
     * Save the route for this method to PayMob dashboard >> response callback route.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return Response
     */
    public function invoice(Request $request)
    {
        # code...
    }
}
