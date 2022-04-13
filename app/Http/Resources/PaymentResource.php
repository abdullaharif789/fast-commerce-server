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
        $date_day = Carbon::parse($this->date)->day;
        $now_day = Carbon::now()->day;
        return[
            'id' => $this->id,
            'name' => $this->name,
            'country' => $this->country,
            'date' => $this->date,
            'remaining_days' => $date_day - $now_day,
            'fee' => $this->fee,
            'advance' => $this->advance,
            'sharing' => $this->sharing,
        ];
    }
}
