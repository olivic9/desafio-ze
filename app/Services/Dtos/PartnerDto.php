<?php

namespace App\Services\Dtos;

use GeoJson\Geometry\MultiPolygon;
use GeoJson\Geometry\Point;
use Illuminate\Support\Facades\DB;
use MStaack\LaravelPostgis\Geometries\Point as MSPoint;
use YucaDoo\LaravelGeoJsonRule\GeoJsonRule;

class PartnerDto extends BaseAbstractDto
{
    private $tradingName;
    private $ownerName;
    private $document;
    private $coverageArea;
    private $address;

    /**
     * @return array
     */
    public function mapToCreate(): array
    {
        $multiPolygon = json_encode($this->coverageArea);

        return [
            'tradingName' => $this->tradingName,
            'ownerName' => $this->ownerName,
            'document' => $this->document,
            'coverageArea' => DB::raw("public.ST_GeomFromGeoJson('$multiPolygon')"),
            'address' => new MSPOINT($this->address['coordinates'][0], $this->address['coordinates'][1])
        ];
    }

    protected function configureValidatorRules(): array
    {
        return [
            'tradingName' => 'required|string|max:255|min:5',
            'ownerName' => 'required|string|max:255|min:5',
            'document' => 'required|string|max:255|min:5|unique:partners',
            'coverageArea' => new GeoJsonRule(MultiPolygon::class),
            'address' => new GeoJsonRule(Point::class)
        ];
    }

    protected function map(array $data): bool
    {
        $this->tradingName = $data['tradingName'];
        $this->ownerName = $data['ownerName'];
        $this->document = $data['document'];
        $this->coverageArea = $data['coverageArea'];
        $this->address = $data['address'];

        return true;
    }

}
