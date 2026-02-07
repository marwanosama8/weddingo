<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Custome\Response;
use App\Http\Requests\UpdateDeviceTokenRequest;
use App\Http\Requests\UpdateImageRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Partner;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

/**
 * @group Blog
 *
 * APIs for Blog Module
 */
class UserController extends Controller
{

  use Response;
  /**
   * 
   *  User And Partner Details
   *
   *  This endpoint allows you to get User And Partner Details.
   * 
   * @authenticated
   *   @response {
   *   Data: {Data},
   * }
   */
  public function user(Request $request)
  {

    $user = $request->user();
    $user->token = $request->bearerToken();
    $user->is_partner = $user->partner ? true : false;
    $user->partner = $user->partners ? $user->partners : null;
    if ($request->user()->getFirstMediaUrl()) {
      $user->has_image = true;
      $user->image_url = $request->user()->getFirstMediaUrl();
    } else {
      $user->has_image = false;
      $user->image_url = null;
    }

    return $this->successEnvelope($user);
  }


  /**
   * 
   *  Edit User
   *
   *  This endpoint allows you to get edit user  .
   * 
   * @bodyParam image string required . 
   * @bodyParam caption string required . 
   * @authenticated
   *   @response {
   *   Data: {Data},
   * }
   */
  public function edit(UpdateUserRequest $request)
  {
    $validator = $request->validated();

    $user = $request->user();

    $user->first_name = $validator['first_name'];
    $user->last_name = $validator['last_name'];

    $user->gender = $validator['gender'];
    if (strlen($validator['date_of_birth']) > 1) {
      $user->birth_date = Carbon::createFromFormat('Y-m-d', $validator['date_of_birth'])->toDateString();
    }
    $user->save();
    return $this->messageResponse(true, 'User Updated Sucsessfullt');
  }


  /**
   * 
   *  Edit User FCM Device Token 
   *
   *  This endpoint allows you to get edit user  .
   * 
   * @bodyParam token string required . 
   * @authenticated
   *   @response {
   *   Data: {Data},
   * }
   */
  public function updateDeviceToken(UpdateDeviceTokenRequest $request)
  {
    $validator = $request->validated();

    $user = $request->user();

    $user->device_token = $validator['token'];
    $user->save();
    return $this->messageResponse('User Updated Sucsessfullt');
  }


  /**
   * block user.
   *
   * @param  int  $id
   * @return Response
   */
  public function blockUser(User $user)
  {
    if ($user->is_blocked) {
      $user->is_blocked = false;
    } else {
      $user->is_blocked = true;
    }
    $user->save();
    return redirect()->back();
  }
}
