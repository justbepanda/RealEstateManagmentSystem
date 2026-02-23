<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ComplexStatResource;
use App\Models\Complex;
use App\Enums\PremiseStatusEnum;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Cache;

/**
 *
 */
class ComplexStatController extends Controller
{
    /**
     * @return AnonymousResourceCollection
     */
    public function index()
    {
        return Cache::tags(['statistics', 'complexes'])->remember('api_complex_stats', now()->addMinutes(30), function () {

            $stats = Complex::query()
                ->withCount([
                    'premises',
                    'premises as available_count' => fn($q) => $q->where('status', PremiseStatusEnum::AVAILABLE->value),
                    'premises as reserved_count'  => fn($q) => $q->where('status', PremiseStatusEnum::RESERVED->value),
                    'premises as sold_count'      => fn($q) => $q->where('status', PremiseStatusEnum::SOLD->value),
                ])
                ->get();

            return ComplexStatResource::collection($stats);
        });
    }
}
