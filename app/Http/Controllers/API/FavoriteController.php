<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Custome\Response;
use App\Http\Requests\StoreFavoriteRequest;
use App\Http\Resources\FavoriteResource;
use App\Models\Favorite;
use Illuminate\Http\Request;
/**
 * @group Favorite
 *
 * APIs for Favorite Module
 */
class FavoriteController extends Controller
{
  use Response;
        /**
     * 
     *  Show all favorites
     *
     *  This endpoint allows you to get all favorites .
     * 
     * @authenticated
     *   @response {
     *   Data: {Data},
     * }
     */
  public function index(Request $request)
  {
    $data = $request->user()->favorites;
    if (!$data) {
      return $this->messageResponse('There Is No Favorites Partners Right Now.');
    }
    return $this->successEnvelope(FavoriteResource::collection($data));
  }


        /**
     * 
     *  Store Favorite
     *
     *  This endpoint allows you to get store blogs .
     * 
     * @bodyParam partner_id string required . 
     * @authenticated
     *   @response {
     *   Data: {Data},
     * }
     */
  public function store(StoreFavoriteRequest $request)
  {

    $validator = $request->validated();
     $userFavts = $request->user()->favorites;
    if (count($userFavts)) {
     $check = $userFavts->where('partner_id', $validator['partner_id'])->first();
      if ($check) {
        $check->delete();
        return $this->messageResponse('UnFavoite');
      } 
    }
    $user = $request->user()->favorites()->create([
      'partner_id' => $validator['partner_id']
    ]);
    $user->save();

    return $this->messageResponse('Partner Add To Favorite Successfully');
  }
}
