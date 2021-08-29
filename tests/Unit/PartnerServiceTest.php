<?php

namespace Tests\Unit;

use App\Models\Partner;
use App\Repositories\Contracts\PartnerInterface;
use App\Services\Dtos\PartnerDto;
use App\Services\PartnerService;
use App\Traits\Util;
use Exception;
use Illuminate\Support\Facades\App;
use InvalidArgumentException;
use Tests\TestCase;
use TypeError;


class PartnerServiceTest extends TestCase
{


    public function test_has_partner_service()
    {
        $this->assertTrue(class_exists(PartnerService::class));
    }

    /**
     * @throws Exception
     */
    public function test_partner_service_create()
    {
        $serviceData = Util::testData();

        $dto = new PartnerDto($serviceData);

        $service = PartnerService::make($dto);

        $partnerInterface = App::make(PartnerInterface::class);

        $response = $service->create($partnerInterface);

        $this->assertIsArray($response);

        $this->assertDatabaseHas('partners', [
            'id' => $response['id']
        ]);

        Partner::destroy($response['id']);

        $this->assertDatabaseMissing('partners', [
            'id' => $response['id'],
            'tradingName' => $response['tradingName'],
            'ownerName' => $response['ownerName'],
            'document' => $response['document'],
        ]);
    }

    public function test_partner_service_create_valdation_error()
    {
        $serviceData = Util::testData();

        $serviceData['address'] = 'error';

        $this->expectException(InvalidArgumentException::class);

        new PartnerDto($serviceData);
    }



}
