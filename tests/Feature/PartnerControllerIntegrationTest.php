<?php

namespace Tests\Feature;

use App\Http\Controllers\PartnerController;
use App\Models\Partner;
use App\Traits\Util;
use Tests\TestCase;
use Throwable;


class PartnerControllerIntegrationTest extends TestCase
{
    public function test_has_partner_controller()
    {
        $this->assertTrue(class_exists(PartnerController::class));
    }

    /**
     * @throws Throwable
     */
    public function test_create_api()
    {
        $data = Util::testData();


        $response = $this->postJson(
            '/api/partners/',
            $data
        );

        $responseSuccess = $response->decodeResponseJson()['success'];

        if ($responseSuccess) {
            $this->assertIsArray($data);
            $response
                ->assertJsonFragment([
                                         'success' => true,
                                         'message' => 'ok',
                                         'tradingName' => $data['tradingName'],
                                         'ownerName' => $data['ownerName'],
                                         'document' => $data['document'],
                                         'coverageArea' => $response['data']['coverageArea'],
                                         'address' => $response['data']['address']
                                     ])
                ->assertStatus(200);
            $this->assertDatabaseHas('partners', [
                'id' => $response['data']['id']
            ]);
            Partner::destroy($response['data']['id']);
            $this->assertDatabaseMissing('partners', [
                'id' => $response['data']['id'],
                'tradingName' => $data['tradingName'],
                'ownerName' => $data['ownerName'],
                'document' => $data['document'],
            ]);
        }
    }

    public function test_get_api()
    {
        $id = 1;
        $response = $this->json('GET', "/api/partners/$id");
        $responseSuccess = $response->decodeResponseJson()['success'];

        if ($responseSuccess) {
            $response
                ->assertJsonFragment([
                                         'success' => true,
                                         'message' => 'ok',
                                         'coverageArea' => $response['data']['coverageArea'],
                                         'address' => $response['data']['address']
                                     ])
                ->assertStatus(200);
            $this->assertDatabaseHas('partners', [
                'id' => $id
            ]);
        }
    }

    /**
     * @throws Throwable
     */
    public function test_find_api()
    {
        $response = $this->postJson(
            'api/partners/find',
            ['lat' => -3.7979, 'long' => -38.5428]
        );
        $responseSuccess = $response->decodeResponseJson()['success'];

        if ($responseSuccess) {
            $response
                ->assertJsonFragment([
                                         'success' => true,
                                         'message' => 'ok',
                                     ])
                ->assertStatus(200);
        }
    }
}
