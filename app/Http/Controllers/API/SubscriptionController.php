<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Custome\Response;
use App\Http\Requests\StoreSubscriptionRequest;
use App\Http\Requests\UpdateSubscriptionRequest;
use App\Models\Subscription;
/**
 * @group Subscriptions
 *
 * APIs for Subscriptions Module
 */
class SubscriptionController extends Controller
{
   
   use Response;

    /**
     * 
     *  Get All Subscriptions
     *
     *  This endpoint allows you to Get All Subscriptions .
     * 
     * @authenticated
     *   @response {
     *   Data: {Data},
     * }
     */
   public function getSubscriptions()
   {
      return $this->successEnvelope(Subscription::all());
   }


   /**
    * Display a listing of the subscriptions.
    *
    * @return Response
    */
   public function payForSubscription(StoreSubscriptionRequest $request)
   {
      return $this->successEnvelope(Subscription::all());
   }
}
