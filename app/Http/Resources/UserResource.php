<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "phone" => $this->phone,
            "email" => $this->email,
            "gender" => $this->gender,
            "birth_date" => $this->birth_date,
            "provider_name" => $this->provider_name,
            "provider_id" => $this->provider_id,
            "country_id" => $this->country_id,
            "city_id" => $this->city_id,
            "is_blocked" => $this->is_blocked,
            "created_at" => $this->created_at,
            "updated_at" =>$this->updated_at
        ];
    }
}
