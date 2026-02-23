<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 *
 */
class ComplexStatResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'    => $this->id,
            'name'  => $this->name,
            'premise_stat' => [
                'total_units' => (int) $this->premises_count,
                'available'   => (int) $this->available_count,
                'reserved'    => (int) $this->reserved_count,
                'sold'        => (int) $this->sold_count,
            ],
        ];
    }
}
