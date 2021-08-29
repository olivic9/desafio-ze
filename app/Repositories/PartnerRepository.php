<?php

namespace App\Repositories;

use App\Models\Partner;
use App\Repositories\Contracts\PartnerInterface;
use Illuminate\Support\Facades\Cache;

class PartnerRepository extends BaseRepository implements PartnerInterface
{

    /**
     * Partner constructor.
     *
     * @param Partner $model
     */
    public function __construct(Partner $model)
    {
        parent::__construct($model);
    }

    public function get(int $id): array
    {
        return Cache::remember(
            "partner_$id",
            1800,
            function () use ($id) {
                return $this->findOrFail($id)->toArray();
            }
        );
    }

    public function findNearestPartner(float $latitude, float $longitude): array
    {
        $queryBuilderBindings = ['long' => $longitude, 'lat' => $latitude];

        $nearestPartner = Cache::remember(
            "lat/$latitude/long/$latitude",
            300,
            function () use ($queryBuilderBindings) {
                return $this->model::whereRaw(
                    'st_covers("coverageArea", ST_SetSRID(ST_MakePoint(:long,:lat)::geography, 4326)) is true',
                    $queryBuilderBindings
                )
                    ->orderByRaw('address <->ST_SetSRID(ST_MakePoint(:long,:lat), 4326)::geography')
                    ->first();
            }
        );

        return !is_null($nearestPartner) ? $nearestPartner->toArray() : [];
    }
}
