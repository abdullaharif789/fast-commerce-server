<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RegistrationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return[
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'contact' => $this->contact,
            'region' => ucwords($this->region),
            'course' => ucwords(str_replace("_"," ",$this->course)),
            'transaction_id' => $this->transaction_id,
            'fee' => $this->fee,
            'registered_at' => $this->created_at,
            'national_identity' => $this->national_identity ? $this->national_identity : "-"
        ];
    }
}
