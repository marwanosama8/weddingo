<?php

namespace App\Http\Resources;

use App\Models\Partner;
use App\Models\PartnerPriceList;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class PartnerResservasionsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $priceList_ids = DB::table('resservasion_pricelists')->where('resservasion_id', $this->id)->pluck('pricelist_id')->toArray();
        $pricelists =  PartnerPriceList::whereIn('id', $priceList_ids)->get();
        $user = User::find($this->user_id);
        return [
            'id' => $this->id,
            // 'user_name' => $user->first_name . ' ' . $user->last_name,
            'user_name' => $user->name,
            'user_id' => Partner::find($this->partner_id)->id,
            'status' => ucfirst($this->status),
            'date_time' => $this->date_time,
            'total_price' => $this->total_price,
            'price_list' => PriceListResource::collection($pricelists)
        ];
    }
}
