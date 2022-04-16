<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
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
            'name' => $this->name,
            'country' => $this->country,
            'service' => $this->service,
            'date' => $this->date,
            'fee' => $this->fee,
            'advance' => $this->advance,
            'sharing' => $this->sharing,
            'contract_duration' => $this->contract_duration,
            'user_id' => $this->user_id,
            'created_at' => $this->created_at,
            'payment_verified' => $this->payment_verified ? "Yes":"No",
            'payment_verified_b' => $this->payment_verified?true:false,
            'document' => $this->document ? url("storage/documents/".$this->document) : null,
            'user' => $this->user

        ];
    }
}
