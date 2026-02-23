<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PremiseResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'number' => $this->number,
            'type' => $this->type,
            'status' => $this->status,
            'rooms' => $this->rooms,
            'prices' => [
                'base' => $this->price_base,
                'discount' => $this->price_discount,
                'per_m2' => $this->price_per_m2,
                'currency' => 'RUB',
            ],
            'areas' => [
                'total' => $this->area_total,
                'living' => $this->area_living,
                'kitchen' => $this->area_kitchen,
            ],
            'features' => $this->features,

            'images' => $this->attachment->map(fn($file) => [
                'id'   => $file->id,
                'url'  => $file->relativeUrl,
                'name' => $file->original_name,
            ]),
            'floor_id' => $this->floor_id,
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
