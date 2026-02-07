<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Custome\Response;
use App\Http\Requests\DeletePartnerPriceListRequest;
use App\Http\Requests\ShowPartnerPriceListReques;
use App\Http\Requests\StorePartnerPriceListReques;
use App\Http\Requests\UpdatePartnerPriceListRequest;
use App\Models\Partner;
use App\Models\PartnerPriceList;
use Illuminate\Http\Request;

/**
 * @group Partner Price List
 *
 * APIs for Partner Price List Module
 */
class PartnerPriceListController extends Controller
{
  use Response;
  /**
   * 
   *  Store PriceList
   *
   *  This endpoint allows you to get store PriceList .
   * 
   * @bodyParam price string required . 
   * @bodyParam service string required . 
   * @authenticated
   *   @response {
   *   Data: {Data},
   * }
   */
  public function store(StorePartnerPriceListReques $request)
  {
    $validator = $request->validated();

    $partnerId = $request->user()->partner->id;

    $review = new PartnerPriceList();
    $review->partner_id = $partnerId;
    $review->price = !empty($validator['price']) ? $validator['price'] : 0;
    $review->service = !empty($validator['service']) ? $validator['service'] : '';
    $review->save();

    return $this->messageResponse(true, 'Partner Price List Stored Successfully');
  }
  /**
   * 
   *  Show Partner PriceList
   *
   *  This endpoint allows you to Show Partner PriceList .
   * 
   * @bodyParam partner_id string required . 
   * @authenticated
   *   @response {
   *   Data: {Data},
   * }
   */
  public function show(ShowPartnerPriceListReques $request)
  {
    $validator = $request->validated();

    $partnerPrice = Partner::find($validator['partner_id'])->priceLists()->get(['id', 'service', 'price']);

    return $this->successEnvelope($partnerPrice);
  }
  /**
   * 
   *  Edit Pricelist
   *
   *  This endpoint allows you to get edit pricelist .
   * 
   * @bodyParam pricelist_id string required . 
   * @bodyParam price string required . 
   * @bodyParam service string required . 
   * @authenticated
   *   @response {
   *   Data: {Data},
   * }
   */
  public function edit(UpdatePartnerPriceListRequest $request)
  {
    $validator = $request->validated();

    $priceList = PartnerPriceList::find($validator['pricelist_id']);
    if (!$priceList) {
      return $this->handleError('There is no price list with this id');
    }
    $priceList->update([
      'price' => $validator['price'],
      'service' => $validator['service'],
    ]);

    return $this->successEnvelope($priceList);
  }
  /**
   * 
   *  Delete Pricelist
   *
   *  This endpoint allows you to get delete pricelist .
   * 
   * @bodyParam pricelist_id string required . 
   * @authenticated
   *   @response {
   *   Data: {Data},
   * }
   */
  public function delete(DeletePartnerPriceListRequest $request)
  {
    $validator = $request->validated();

    $pricelist = PartnerPriceList::find($validator['pricelist_id']);
    $pricelist->delete();

    return $this->messageResponse('Deleted');
  }
}
