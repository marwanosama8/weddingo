<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Custome\Response;
use App\Http\Resources\AdvertisementResource;
use App\Models\Advertisement;
use Illuminate\Http\Request;

/**
 * @group Advertisement
 *
 * APIs for Advertisement Module
 */
class AdvertisementController extends Controller
{
    use Response;

    /**
     * 
     * All Advertisements 
     *
     *  This endpoint allows you to Partner show all Advertisements .
     * 
     * @authenticated
     *   @response {
     *   Data: {Data},
     * }
     */
    public function index()
    {
        $data = Advertisement::all();

        return AdvertisementResource::collection($data);
    }
}
