<?php

namespace App\Http\Resources;

use App\Models\Partner;
use App\Models\PartnerPriceList;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class UserResservasionsResource extends JsonResource
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
        return [
            'id' =>$this->id,
            'partner_name' => Partner::find($this->partner_id)->business_name,
            'partner_id' => Partner::find($this->partner_id)->id,
            'status' => ucfirst($this->status),
            'date_time' => $this->date_time,
            'total_price' => $this->total_price,
            'price_list' => PriceListResource::collection($pricelists)
        ];
    }
}
