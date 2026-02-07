<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Custome\Response;
use App\Http\Requests\ShowBlogByCategory;
use App\Http\Requests\ShowPartnerByCategoryRequest;
use App\Http\Requests\ShowPartnerRequest;
use App\Http\Requests\StoreImageToGalleryRequest;
use App\Http\Requests\StorePartnerRequest;
use App\Http\Requests\UpdatePartnerRequest;
use App\Http\Resources\MediaBlogResource;
use App\Http\Resources\PartnerResource;
use App\Models\Category;
use App\Models\GalleryBlog;
use App\Models\Partner;
use Illuminate\Http\Request;

/**
 * @group Partner
 *
 * APIs for PArtner Module
 */
class PartnerController extends Controller
{
  use Response;

  /**
   * 
   *  Store Partner
   *
   *  This endpoint allows you to get store Partner .
   * 
   * @bodyParam category_id string
   * @bodyParam other_categroy_id string
   * @bodyParam business_name string
   * @bodyParam social_provider string
   * @bodyParam social_url string
   * @bodyParam bio string
   * @bodyParam business_type string
   * @bodyParam about_us_survey string
   * @bodyParam weekends string
   * @bodyParam address_address string
   * @bodyParam address_latitude string
   * @bodyParam address_longitude string
   * 
   * 
   * @authenticated
   * 
   *   @response {
   *   Data: {Data},
   * }
   */
  public function store(StorePartnerRequest $request)
  {
    $validator = $request->validated();

    $user = $request->user();

    $partner = new Partner();
    $partner->user_id = $user->id;
    // $partner->category_id = $validator['category_id'];
    // $partner->other_categroy_id = $validator['other_categroy_id'];
    $partner->business_name = $validator['business_name'];
    $partner->social_provider = $validator['social_provider'];
    $partner->social_url = $validator['social_url'];
    $partner->business_type = $validator['business_type'];
    $partner->about_us_survey = $validator['about_us_survey'];
    $partner->weekends = $validator['weekends'];
    $partner->address_address = $validator['address_address'] ?? '';
    $partner->address_latitude = $validator['address_latitude'] ?? 0;
    $partner->address_longitude = $validator['address_longitude'] ?? 0;
    $partner->save();

    $partner->categories()->sync([$validator['category_id']], ...$validator['other_categroy_id']);

    return $this->messageResponse('Partner created successfully!');
  }



  /**
   * 
   *  Edit Partner
   *
   *  This endpoint allows you to edit partner .
   * 
   * @bodyParam category_id string required
   * @bodyParam other_categroy_id string required
   * @bodyParam business_name string required
   * @bodyParam social_provider string required
   * @bodyParam social_url string required
   * @bodyParam bio string required
   * @bodyParam business_type string required
   * @bodyParam about_us_survey string required
   * @bodyParam weekends string required
   * @bodyParam address_address string required
   * @bodyParam address_latitude string required
   * @bodyParam address_longitude string required
   * @authenticated
   *   @response {
   *   Data: {Data},
   * }
   */
  public function edit(StorePartnerRequest $request)
  {
    $validator = $request->validated();
    $partner = Partner::find($request->user()->partner->id);
    if (!$partner) {
      return $this->handleError('There is no partner with this id ');
    }
    $partner->update([
      'business_name' => $validator['business_name'],
      'social_provider' => $validator['social_provider'],
      'social_url' => $validator['social_url'],
      'business_type' => $validator['business_type'],
      'weekends' => $validator['weekends'],
      'about_us_survey' => $validator['about_us_survey'],
      'address_address' => $validator['address_address'],
      'address_latitude' => $validator['address_latitude'],
      'address_longitude' => $validator['address_longitude'],
    ]);

    return $this->messageResponse('Partner Updated Successfully');
  }
  /**
   * 
   *  Show Specific Partner
   *
   *  This endpoint allows you to get show specific partner by id .
   * 
   * @bodyParam partner_id string required . 
   * @authenticated
   *   @response {
   *   Data: {Data},
   * }
   */
  public function show(ShowPartnerRequest $request)
  {
    $validator = $request->validated();
    $partner = Partner::find($validator['partner_id']);
    if (!$partner) {
      return $this->handleError('There is no partner with this id ');;
    }
    return $this->successEnvelope(PartnerResource::make($partner));
  }



  /**
   * 
   *  Show Partners In Specific Category
   *
   *  This endpoint allows you to get Show Partners In Specific Category, by distance if can.
   * 
   * @bodyParam category_id string required . 
   * @bodyParam latitude string  . 
   * @bodyParam longitude string  . 
   * @authenticated
   *   @response {
   *   Data: {Data},
   * }
   */
  public function showPartnersByCategory(ShowPartnerByCategoryRequest $request)
  {
    $validator = $request->validated();

    Category::find($validator['category_id'])->increment('viewes_count');
    // $category = Category::find($validator['category_id'])->partners()->active()->orderBy('created_at')->get();
    $category = Category::find($validator['category_id'])->partners()->active()->get();
    if ($request->has('latitude') && $request->has('longitude')) {
      $userLatitude = $request->get('latitude');
      $userLongitude = $request->get('longitude');
      $asd =  $category->map(function ($cat) use ($userLatitude, $userLongitude) {
        $catDistance = $this->distance($userLatitude, $userLongitude, $cat->address_latitude, $cat->address_longitude);
        return $asd = [
          'cat' => $cat,
          'distance' => $catDistance,
        ];
      });
      return $asd->sortBy('distance')->toArray();
    } else {
      return $this->successEnvelope(PartnerResource::collection($category));
    }
  }


  public function acceptPartner(Partner $partner)
  {
    if ($partner->active) {
      $partner->active = false;
    } else {
      $partner->active = true;
    }
    $partner->save();
    return redirect()->back();
  }
  private function distance($lat1, $lon1, $lat2, $lon2)
  {
    $earthRadius = 6371; // km
    $dLat = deg2rad($lat2 - $lat1);
    $dLon = deg2rad($lon2 - $lon1);
    $a = sin($dLat / 2) * sin($dLat / 2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon / 2) * sin($dLon / 2);
    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
    $distance = $earthRadius * $c;
    return $distance;
  }
}
