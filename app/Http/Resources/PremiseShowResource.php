<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PremiseShowResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'     => $this->id,
            'number' => $this->number,
            'status' => $this->status,
            'type'   => $this->type,
            'rooms'  => $this->rooms,

            // Информация о расположении
            'location' => [
                'complex'  => $this->floor->section->building->complex->name ?? null,
                'building' => $this->floor->section->building->name ?? null,
                'section'  => $this->floor->section->name ?? null,
                'floor'    => $this->floor->number,
            ],

            'metrics' => [
                'area_total'   => $this->area_total,
                'area_living'  => $this->area_living,
                'area_kitchen' => $this->area_kitchen,
            ],

            'prices' => [
                'base'     => $this->price_base,
                'discount' => $this->price_discount,
                'per_m2'   => $this->price_per_m2,
            ],

            'features' => $this->features,

            'images' => $this->attachment->map(fn($file) => [
                'id'   => $file->id,
                'url'  => $file->relativeUrl,
                'name' => $file->original_name,
            ]),

            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
