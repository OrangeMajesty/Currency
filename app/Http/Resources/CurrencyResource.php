<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CurrencyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->type->name,
            'code' => $this->type->code,
            'rate' => round($this->rate / 10000, 4),
            'created_at' => $this->created_at,
        ];
    }
}
