<?php

namespace App\Http\Controllers;

use App\Http\Custome\Response;
use App\Http\Requests\makeRequset;
use App\Models\Partner;
use App\Models\PartnerRequest;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
/**
 * @group Point Exchange
 *
 * APIs for Point Exchange Module
 */
class PartnerRequestController extends Controller
{

    use Response;
    /**
     * 
     *  Make Request
     *
     *  This endpoint allows you to Make Request to subscribtion .
     * 
     * @bodyParam subsciption_id string required . 
     * @bodyParam partner_id string required . 
     * @authenticated
     *   @response {
     *   Data: {Data},
     * }
     */
    public function makeRequset(makeRequset $requset)
    {
        $validator = $requset->validated();
        $subb = Subscription::find($validator['subsciption_id']);
        $partner = Partner::find($validator['partner_id']);

        if (!$partner->points >= $subb->subscription_request) {
            return $this->handleError('Your Points Not Enough');
        }

        PartnerRequest::create([
            'partner_id' => $validator['partner_id'],
            'subscription_id' => $validator['subsciption_id'],
        ]);

        return $this->messageResponse('Done');
    }

    public function acceptRequest(PartnerRequest $partnerrequest)
    {
        $partner = Partner::find($partnerrequest->partner_id);
        $subb = Subscription::find($partnerrequest->subscription_id);
        // dd($points = $partner->points - $subb->subscription_request);
        $store = DB::table('user_subscriptions')->insert([
            'user_id' => $partner->user->id,
            'subsciption_id' => $partnerrequest->subscription_id,
            'payment_method' => 'Points',
            'total_price' => $subb->subscription_request,
            'transaction_number' => "0",
        ]);
        $parnter = $partner->update([
            'gallery_limit' => $subb->blog_per_month
        ]);
        $points = $partner->points - $subb->subscription_request;
        $partner->update([
            "points" => $points
        ]);

        $partnerrequest->accepted = 1;
        $partnerrequest->save();

        return redirect()->back();
    }
}
