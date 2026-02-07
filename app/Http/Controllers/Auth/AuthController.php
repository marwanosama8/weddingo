<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Custome\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Laravel\Socialite\Facades\Socialite;

/**
 * @group Auth 
 *
 * APIs for authantiucation
 */
class AuthController extends Controller
{

    use Response;


    /**
     * 
     *  Login
     *
     *  This endpoint allows you to login and get barear token .
     * 
     * @bodyParam phone string required The phone number. Example: '010223123'
     * @bodyParam password string required The password. Example: 'qwr@qwea'
     * 
     *   @response {
     *   "token_type": "Bearer",
     *   "fullname": 'Marwan Osama',
     *   "username": 'marwanosama',
     *   "country": '1',
     *   "city": '3',
     *   "phone": '01233310',
     * }
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|string',
            'password' => 'required|string|min:6'
        ]);

        if ($validator->fails()) {
            return $this->handleError($validator->errors(), 422);
        } else {
            $check = Auth::attempt([
                "phone" => $request->get('phone'),
                "password" => $request->get('password')
            ]);

            if ($check) {
                $auth = Auth::user();

                $success = [
                    "token" => $auth->createToken('LaravelSanctumAuth')->plainTextToken,
                    "token_type" => "Bearer",
                    "name" => $auth->fullname,

                ];

                return $this->handleResponse($success, 'Login Success!');
            } else {
                return $this->handleError(
                    ['error' => 'Phone Or Password Not Vaild'],
                    403
                );
            }
        }
    }
    /**
     * 
     *  Create
     *
     *  This endpoint allows you to create new user and get barear token .
     * 
     * @bodyParam fullname string required The full name. Example: 'John Doo'
     * @bodyParam username string required The Username. Example: 'dohn_doo'
     * @bodyParam password string required The password. Example: 'qwr@qwea'
     * @bodyParam phone string required The phone. Example: '0123123'
     * @bodyParam image string The image path. Example: 'file:///home/marwan/Pictures/WhatsApp_logo-color-vertical.svg.png'
     * 
     *   @response {
     *   "token": '1239801273987123089127-38901273',
     *   "user": 'user data'
     * }
     */
    public function register(StoreUserRequest $request)
    {

        $validator = $request->validated();
        $user = new User();
        $user->phone      = $validator['phone'];
        $user->password   = bcrypt($validator['password']);
        $user->save();

        //Geneate A Code
        $token = $user->createToken('LaravelSanctumAuth')->plainTextToken;
        //$user->code = substr(number_format(time() * rand(), 0, '', ''), 0, 6);
        // if ($request->has('image')) {
        //     $user->addMediaFromRequest('image')->toMediaCollection('avatar');
        // } else {
        //     $url = 'https://eu.ui-avatars.com/api/?rounded=true&name=' . $user->fullname;
        //     $user->addMediaFromUrl($url)->toMediaCollection('avatar');
        // }
        // Mail::to($customer)->send(new EmailCode($customer->code));

        $response = [
            "token" => $token,
            "user" => $user
        ];

        return $this->handleResponse($response, 'User Has Been Creaded Success');
    }


    public function user(Request $request)
    {

        return [
            "token" => $request->bearerToken(),
            "fullname" => $request->user()->fullname,
            "username" => $request->user()->username,
            "country" => $request->user()->country_id,
            "city" => $request->user()->city_id,
            "phone" => $request->user()->phone,
        ];
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|string|email',
            'phone' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->handleError($validator->errors(), 422);
        } else {
            $user = User::find($request->user()->id);
            $request->has('first_name') ? $user->first_name = $request->get('first_name') : "";
            $request->has('mid_name') ? $user->mid_name = $request->get('mid_name') : "";
            $request->has('last_name') ? $user->last_name = $request->get('last_name') : "";
            $request->has('phone') ? $user->phone = $request->get('phone') : "";
            $request->has('email') ? $user->email = $request->get('email') : "";
            $request->has('country_id') ? $user->country_id = $request->get('country_id') : "";
            $request->has('city_id') ? $user->city_id = $request->get('city_id') : "";
            $request->has('area_id') ? $user->area_id = $request->get('area_id') : "";
            $request->has('long') ? $user->long = $request->get('long') : "";
            $request->has('lat') ? $user->lat = $request->get('lat') : "";
            $user->save();

            $user->name = "";

            if ($user->first_name) {
                $user->name .= $user->first_name;
            }
            if ($user->mid_name) {
                $user->name .= ' ' . $user->mid_name . ' ';
            }
            if ($user->last_name) {
                $user->name .= $user->last_name;
            }

            $user->save();


            return [
                "name" => $user->name,
                "token_type" => "Bearer",
                "address" => $user->address,
                "mid_name" => $user->mid_name,
                "first_name" => $user->first_name,
                "token" => $user->createToken('LaravelSanctumAuth')->plainTextToken,
                "last_name" => $user->last_name,
                "country" => $user->country,
                "email" => $user->email,
                "phone" => $user->phone,
                "long" => $user->long,
                "city" => $user->city,
                "area" => $user->area,
                "lat" => $user->lat
            ];
        }
    }
    /**
     * 
     *  logout
     *
     *  This endpoint allows you to logout .
     * @authenticated

     * 
     *   @response {
     *   "message": 'User Logged Out Seccess',
     * }
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return $this->successEnvelope(200, 'User Logged Out Seccess');
    }

    /**
     *  Social Login
     *
     *  This endpoint allows you to login and get social login and get barear token .
     * 
     * @bodyParam provider_name string required .
     * @bodyParam access_token string required .
     * 
     *   @response {
     *   Data: {Data},
     * }
     */
    public function socialLogin(Request $request)
    {

        $rules = [
            'provider_name' => 'required|string',
            'access_token' => 'required|string',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            // Handle validation errors
            return response()->json([
                'errors' => $validator->errors(),
            ], 422);
        }

        $provider = $request->input('provider_name'); //for multiple providers
        $token = $request->input('access_token');
        // get the provider's user. (In the provider server)
        $providerUser = Socialite::driver($provider)->userFromToken($token);
        // check if access token exists etc..
        // search for a user in our server with the specified provider id and provider name
        $user = User::where('provider_name', $provider)->where('provider_id', $providerUser->id)->first();
        // if there is no record with these data, create a new user
        if ($user == null) {
            $user = User::create([
                'provider_name' => $provider,
                'provider_id' => $providerUser->id,
            ]);
        }
        // create a token for the user, so they can login
        $token = $user->createToken('LaravelSanctumAuth')->plainTextToken;
        // return the token for usage
        return response()->json([
            'success' => true,
            'token' => $token
        ]);
    }
}
