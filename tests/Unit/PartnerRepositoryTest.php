<?php

namespace Tests\Unit;

use App\Models\Partner;
use App\Repositories\Contracts\PartnerInterface;
use App\Repositories\PartnerRepository;
use App\Traits\Util;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Tests\TestCase;
use Mockery;


class PartnerRepositoryTest extends TestCase
{
    /**
     * @var PartnerRepository|Mockery\LegacyMockInterface|Mockery\MockInterface
     */
    private $mockedRepository;

    public function test_has_partner_repository()
    {
        $this->assertTrue(class_exists(PartnerRepository::class));
    }

    public function test_create_method()
    {
        $serviceData = Util::testData();
        $mockedRepository = Mockery::mock(PartnerRepository::class);
        $partnerMock = Mockery::mock(Model::class);
        $mockedRepository->shouldReceive('create')->with($serviceData)->andReturn($partnerMock);
        $result = $mockedRepository->create($serviceData);
        $this->assertIsObject($result);

    }

    public function test_find_nearest_partner_method()
    {
        $latitude = -3.7979;
        $longitude = -38.5428;
        $this->mockedRepository = Mockery::mock(PartnerRepository::class);
        $partnerMock = Mockery::mock(Partner::class);
        $this->mockedRepository->shouldReceive('findNearestPartner')->with($latitude, $longitude)->andReturn(
            (array)$partnerMock
        );
        $result = $this->mockedRepository->findNearestPartner($latitude, $longitude);
        $this->assertIsArray($result);
    }

    public function test_get_method()
    {
        $id = 100000;
        $this->mockedRepository = Mockery::mock(PartnerRepository::class);
        $partnerMock = Mockery::mock(Partner::class);
        $this->mockedRepository->shouldReceive('get')->with($id)->andReturn((array)$partnerMock);
        $result = $this->mockedRepository->get($id);
        $this->assertIsArray($result);


    }

    public function test_find_nearest_partner()
    {
        $latitude = -3.7979;
        $longitude = -38.5428;
        $partnerInterface = App::make(PartnerInterface::class);
        $partnerData = $partnerInterface->findNearestPartner($latitude, $longitude);
        $this->assertDatabaseHas('partners', [
            'id' => $partnerData['id']
        ]);
    }

    public function test_get()
    {
        $id = 1;
        $partnerInterface = App::make(PartnerInterface::class);
        $partnerData = $partnerInterface->get($id);
        $this->assertDatabaseHas('partners', [
            'id' => $partnerData['id']
        ]);
    }


}
