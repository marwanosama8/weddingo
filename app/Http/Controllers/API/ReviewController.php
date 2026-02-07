<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Custome\Response;
use App\Http\Requests\GetPartnerReviewRequest;
use App\Http\Requests\StoreReviewRequest;
use App\Models\Partner;
use App\Models\Review;
use Illuminate\Http\Request;

/**
 * @group Review
 *
 * APIs for Review Module
 */
class ReviewController extends Controller
{

  use Response;

  /**
   * 
   *  Get Partner Reviews Count 
   *
   *  This endpoint allows you to Get Partner Reviews Count  .
   * 
   * @bodyParam partner_id integer required . 
   * @authenticated
   *   @response {
   *   Data: {Data},
   * }
   */
  public function getPartnerReviewsCount(GetPartnerReviewRequest $request)
  {
    $validator = $request->validated();

    $partner = Partner::find($validator['partner_id']);
    if (!$partner) {
      return $this->handleError('Partner not found');
    } else {
      $rateSum = $partner->reviews->sum('rate');
      $count = $partner->reviews()->count();
      return $this->successEnvelope([
        'count' => $count,
        'rate_avrage' => $partner->reviews->avg('rate')
        // 'rate_avrage' => $rateSum * $count / 100 
      ]);
    }
  }

  /**
   * 
   *  Get Partner Review And Comments
   *
   *  This endpoint allows you to Get Partner Review And Comments .
   * 
   * @bodyParam partner_id integer required . 
   * @authenticated
   *   @response {
   *   Data: {Data},
   * }
   */
  public function GetPartnerReviewsAndComments(GetPartnerReviewRequest $request)
  {
    $validator = $request->validated();
    $partner = Partner::find($validator['partner_id'])->reviews()->get(['rate', 'review','created_at']);
    return $this->successEnvelope($partner = Partner::find($validator['partner_id'])->reviews()->get(['rate', 'review','created_at']));
  }

  /**
   * 
   *  Store Review
   *
   *  This endpoint allows you to store review .
   * 
   * @bodyParam partner_id stringinteger required . 
   * @bodyParam rate string required . 
   * @bodyParam review string required . 
   * @authenticated
   *   @response {
   *   Data: {Data},
   * }
   */
  public function store(StoreReviewRequest $request)
  {
    $validator = $request->validated();

    $review = new Review();
    $review->partner_id = $validator['partner_id'];
    $review->rate = empty($validator['rate']) ? 0 : $validator['rate'];
    $review->review = empty($validator['review']) ? '' : $validator['review'];
    $review->save();

    return $this->messageResponse('Review Stored Successfully');
  }
}
