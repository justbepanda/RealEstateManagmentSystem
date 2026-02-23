<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\PremiseIndexRequest;
use App\Http\Resources\PremiseResource;
use App\Http\Resources\PremiseShowResource;
use App\Models\Premise;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Cache;

class PremiseController extends Controller
{
    /**
     * @param PremiseIndexRequest $request
     * @return AnonymousResourceCollection
     */
    public function index(PremiseIndexRequest $request): AnonymousResourceCollection
    {
        $key = 'premises_list_' . md5(serialize($request->all()));

        return Cache::tags(['premises'])->remember($key, now()->addMinutes(15), function () use ($request) {
            $query = Premise::with('attachment');

            $premises = $query
                ->when($request->status,    fn($q, $v) => $q->where('status', $v))
                ->when($request->rooms,     fn($q, $v) => $q->where('rooms', $v))
                ->when($request->price_min, fn($q, $v) => $q->where('price_base', '>=', $v))
                ->when($request->price_max, fn($q, $v) => $q->where('price_base', '<=', $v))
                ->latest()
                ->paginate($request->integer('per_page', 15));

            return PremiseResource::collection($premises);
        });
    }

    /**
     * Детальная информация о помещении
     */
    public function show(Premise $premise): PremiseShowResource
    {
        $premise->load([
            'attachment',
            'floor.section.building.complex'
        ]);

        return new PremiseShowResource($premise);
    }
}
