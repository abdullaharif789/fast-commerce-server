<?php

namespace App\Http\Resources;
use Carbon\Carbon;

use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $date = Carbon::parse($this->date);
        $now = Carbon::now();
        $diff = $date->diffInDays($now);
        return[
            'id' => $this->id,
            'name' => $this->name,
            'country' => $this->country,
            'date' => $this->date,
            'remaining_days' => (30 - $diff) % 30,
            'fee' => $this->fee,
            'advance' => $this->advance,
            'sharing' => $this->sharing,
        ];
    }
}
