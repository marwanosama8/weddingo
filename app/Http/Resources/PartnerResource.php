<?php

namespace App\Http\Resources;

use App\Models\Partner;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;
use Musonza\Chat\Facades\ChatFacade as Chat;

class PartnerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $favorite = DB::table('favorites')->where('user_id', $request->user()->id)->where('partner_id', $this->id)->first();
        $rateSum = $this->reviews->sum('rate');
        $count = $this->reviews()->count();
        $partner = Partner::find($this->id);
        $user = $request->user();
        $participants = [$partner, $user];
        $conversations = Chat::conversations()->between(...$participants);

        if ($request->is('api/partner/show')) {
            return [
                'id' => $this->id,
                'user_id' => $this->user_id,
                'category_id' => $this->category->name,
                'other_categroy_id' => Partner::find($this->other_catgrory_id),
                'gallery_limit' => $this->gallery_limit,
                'active' => $this->active,
                'business_name' => $this->business_name,
                'social_provider'    => $this->social_provider,
                'business_type' => $this->business_type,
                'bio' => $this->bio,
                'about_us_survey'     => $this->about_us_survey,
                'phone'     => $this->user->phone,
                'has_image' => $this->user->getMedia() ? true : false,
                'image_url' => $this->user->getFirstMediaUrl(),
                'favorite' => $favorite ? true : false,
                'reviews_count' => $count,
                'reviews_rate_avrage' => $partner->reviews->avg('rate'),
                "have_chat_conversation" => $conversations ? true : false,
                "chat_id" => $conversations ? $conversations->id : null,
                'created_at'    => $this->created_at,
                'updated_at'    => $this->updated_at,
            ];
        } else {
            return [
                'id' => $this->id,
                'user_id' => $this->user_id,
                'category_id' => $this->category_id,
                'other_categroy_id' => $this->other_categroy_id,
                'gallery_limit' => $this->gallery_limit,
                'active' => $this->active,
                'business_name' => $this->business_name,
                'social_provider'    => $this->social_provider,
                'bio' => $this->bio,
                'business_type' => $this->business_type,
                'about_us_survey'     => $this->about_us_survey,
                'phono'     => $this->user->phone,
                'has_image' => $this->user->getMedia() ? true : false,
                'image_url' => $this->user->getFirstMediaUrl(),
                'reviews_count' => $count,
                'reviews_rate_avrage' => $partner->reviews->avg('rate'),
                "have_chat_conversation" => $conversations ? true : false,
                "chat_id" => $conversations ? $conversations->id : null,
                'created_at'    => $this->created_at,
                'updated_at'    => $this->updated_at,

            ];
        }
    }
}
