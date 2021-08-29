<?php

namespace App\Services;

use App\Models\Partner;
use App\Repositories\Contracts\PartnerInterface;
use App\Services\Dtos\BaseAbstractDto;
use App\Services\Dtos\PartnerDto;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;
use Exception;
use MStaack\LaravelPostgis\Geometries\LineString;
use MStaack\LaravelPostgis\Geometries\MultiPolygon;
use MStaack\LaravelPostgis\Geometries\Point;

/**
 * Class PartnerService
 * @package App\Services
 */
class PartnerService implements ServiceInterface
{


    /**
     * @var PartnerDto
     */
    private $dto;

    /**
     * @param PartnerDto $dto
     */
    public function __construct(PartnerDto $dto)
    {
        $this->dto = $dto;
    }

    public static function make(BaseAbstractDto $dto): ServiceInterface
    {
        if (!$dto instanceof PartnerDto) {
            throw new InvalidArgumentException(
                'PartnerService needs to receive a PartnerDto.'
            );
        }

        return new PartnerService($dto);
    }

    /**
     * Creates a new partner
     * @return array
     * @throws Exception
     */
    public function create(PartnerInterface $partner): array
    {
        $partnerData = $this->dto->mapToCreate();

        $partner = $partner->create($partnerData);

        return $partner->fresh()->toArray();
    }

}
