<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Custome\Response;
use App\Http\Requests\UpdateImageRequest;
use Illuminate\Http\Request;
/**
 * @group Image Manipulation
 *
 * APIs for Image Module
 */
class ImagesController extends Controller
{
    use Response;
        /**
     * 
     *  Update Image
     *
     *  This endpoint allows you to get update image .
     * 
     * @bodyParam image string required . 
     * @authenticated
     *   @response {
     *   Data: {Data},
     * }
     */
    public function updateImage(UpdateImageRequest $request)
    {
        $validator = $request->validated();
        if ($request->user()->getMedia()) {
            $request->user()->clearMediaCollection();
            $request->user()->addMediafromRequest('image')->toMediaCollection();
            return $this->successEnvelope('User Updated Sucsessfully');
        } else {
            $request->user()->addMediafromRequest('image')->toMediaCollection();
            return $this->successEnvelope('User Updated Sucsessfully');
        }
    }
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function show(Request $request)
    {
        if ($request->user()->getFirstMediaUrl()) {
            return $this->successEnvelope([
                'has_image' => true,
                'image_url' => $request->user()->getFirstMediaUrl()
            ]);
        } else {
            return $this->successEnvelope([
                'has_image' => false,
                'image_url' => null
            ]);
        }
        
    }
}
