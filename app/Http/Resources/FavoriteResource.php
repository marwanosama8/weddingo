<?php

namespace App\Http\Resources;

use App\Models\Partner;
use Illuminate\Http\Resources\Json\JsonResource;

class FavoriteResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $partner = Partner::find($this->partner_id);
        return [
            'partner_id' => $partner->id,
            'partner_name' => $partner->user->name,
            'time' => $this->created_at->diffForHumans()
        ];
    }
}
