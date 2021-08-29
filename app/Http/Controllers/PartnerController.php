<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseController as BaseController;
use App\Models\Partner;
use App\Repositories\Contracts\PartnerInterface;
use App\Services\Dtos\PartnerDto;
use App\Services\PartnerService;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use OpenApi\Annotations as OA;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;
use Illuminate\Support\Facades\Validator;

use function Couchbase\defaultDecoder;


class PartnerController extends BaseController
{
    private $partner;

    public function __construct(PartnerInterface $partner)
    {
        $this->partner = $partner;
    }

    /**
     * @OA\Post(
     *      path="/partners",
     *      operationId="createPartner",
     *      tags={"Partners"},
     *      summary="Creates a new Zé Delivery Partner",
     *      description="Creates a Zé Delivery partner and return a json response",
     * @OA\RequestBody(
     *    required=true,
     *    description="Create partner payload data",
     *    @OA\JsonContent(
     *       @OA\Property(property="tradingName", type="string", format="string", example="Deposito do Zé", description="Partner Trading Name"),
     *       @OA\Property(property="ownerName", type="string", format="string", example="Zé Oliveira", description="Partner Owner Name"),
     *       @OA\Property(property="document", type="string", example="A112x0001", description="Partner Document"),
     *       @OA\Property(property="coverageArea", type="json", example="{}", description="Partner coverage area GeoJson object, type MultiPolygon"),
     *       @OA\Property(property="address", type="json", example="{}", description="Partner address GeoJson object, type point"),
     *    ),
     * ),
     *     @OA\Response(
     *         response="200",
     *         description="ok",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                     @OA\Property(
     *                         property="success",
     *                         type="boolean",
     *                         description="The request was successfull?"
     *                     ),
     *                     @OA\Property(
     *                         property="message",
     *                         type="string",
     *                         description="The response message"
     *                     ),
     *                     @OA\Property(
     *                         property="data",
     *                         type="array",
     *                         description="The response data",
     *                         @OA\Items
     *                     ),
     *                     example={
     *                         "success": true,
     *                          "message": "ok",
     *                          "data": {
     *                              "id": 0,
     *                              "tradingName": "B0r do Ze",
     *                              "ownerName": "J0a Silva",
     *                              "document": "00000",
     *                              "coverageArea": {},
     *                              "address": {}
     *                          }
     *                     }
     *                 )
     *             )
     *         }
     *     ),
     *     @OA\Response(
     *         response="422",
     *         description="Unprocessable Entity",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                     @OA\Property(
     *                         property="success",
     *                         type="boolean",
     *                         description="The request was successfull?"
     *                     ),
     *                     @OA\Property(
     *                         property="message",
     *                         type="string",
     *                         description="The response message"
     *                     ),
     *                     @OA\Property(
     *                         property="data",
     *                         type="array",
     *                         description="The response data",
     *                         @OA\Items
     *                     ),
     *                     example={
     *                         "success": false,
     *                          "message": "error message",
     *                          "data": {}
     *                     }
     *                 )
     *             )
     *         }
     *     ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     * )
     */

    /**
     * Create a new partner.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function create(Request $request)
    {
        try {
            $data = $this->decodeRequest($request);

            $dto = new PartnerDto($data);

            $service = PartnerService::make($dto);

            $response = $service->create($this->partner);

            $httpCode = 200;

            $data = $this->formatResponse(true, $response, 'ok');
        } catch (InvalidArgumentException | Exception $e) {
            $data = $this->formatResponse(false, [], $e->getMessage());
            $httpCode = 422;
            Log::error(__CLASS__ . '/' . __FUNCTION__ . ':' . __LINE__, ['message' => $e->getMessage()]);
        }
        return $this->sendResponse($data, $httpCode);
    }


    /**
     * @OA\Get(
     *      path="/partners/{id}",
     *      operationId="getPartnerById",
     *      tags={"Partners"},
     *      summary="Get Zé Delivery Information",
     *      description="Returns Zé Delivery partner data",
     *      @OA\Parameter(
     *          name="id",
     *          description="Partner id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *     @OA\Response(
     *         response="200",
     *         description="ok",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                     @OA\Property(
     *                         property="success",
     *                         type="boolean",
     *                         description="The request was successfull?"
     *                     ),
     *                     @OA\Property(
     *                         property="message",
     *                         type="string",
     *                         description="The response message"
     *                     ),
     *                     @OA\Property(
     *                         property="data",
     *                         type="array",
     *                         description="The response data",
     *                         @OA\Items
     *                     ),
     *                     example={
     *                         "success": true,
     *                          "message": "ok",
     *                          "data": {
     *                              "id": 0,
     *                              "tradingName": "B0r do Ze",
     *                              "ownerName": "J0a Silva",
     *                              "document": "00000",
     *                              "coverageArea": {},
     *                              "address": {}
     *                          }
     *                     }
     *                 )
     *             )
     *         }
     *     ),
     *     @OA\Response(
     *         response="422",
     *         description="Unprocessable Entity",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                     @OA\Property(
     *                         property="success",
     *                         type="boolean",
     *                         description="The request was successfull?"
     *                     ),
     *                     @OA\Property(
     *                         property="message",
     *                         type="string",
     *                         description="The response message"
     *                     ),
     *                     @OA\Property(
     *                         property="data",
     *                         type="array",
     *                         description="The response data",
     *                         @OA\Items
     *                     ),
     *                     example={
     *                         "success": false,
     *                          "message": "error message",
     *                          "data": {}
     *                     }
     *                 )
     *             )
     *         }
     *     ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     * )
     */


    /**
     * Get partner by id.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function get(int $id)
    {
        try {
            $httpCode = 200;

            $partnerData = $this->partner->get($id);

            $data = $this->formatResponse(true, $partnerData, 'ok');
        } catch (ModelNotFoundException $e) {
            $httpCode = 422;
            $data = $this->formatResponse(false, [], "Partner id $id not found");
            Log::error(__CLASS__ . '/' . __FUNCTION__ . ':' . __LINE__, ['message' => $e->getMessage()]);
        }
        return $this->sendResponse($data, $httpCode);
    }

    /**
     * @OA\Post(
     *      path="/partners/find",
     *      operationId="findPartner",
     *      tags={"Partners"},
     *      summary="Finds Nearest Zé Delivery Partner",
     *      description="Finds nearest Zé Delivery partner based on given latitude and longitude",
     * @OA\RequestBody(
     *    required=true,
     *    description="Payload data",
     *    @OA\JsonContent(
     *       @OA\Property(property="lat", type="float", format="float", example="-3.7979", description="Given latitude"),
     *       @OA\Property(property="long", type="float", format="strfloating", example="-38.5428", description="given longitude"),
     *    ),
     * ),
     *     @OA\Response(
     *         response="200",
     *         description="ok",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                     @OA\Property(
     *                         property="success",
     *                         type="boolean",
     *                         description="The request was successfull?"
     *                     ),
     *                     @OA\Property(
     *                         property="message",
     *                         type="string",
     *                         description="The response message"
     *                     ),
     *                     @OA\Property(
     *                         property="data",
     *                         type="array",
     *                         description="The response data",
     *                         @OA\Items
     *                     ),
     *                     example={
     *                         "success": true,
     *                          "message": "ok",
     *                          "data": {
     *                              "id": 0,
     *                              "tradingName": "B0r do Ze",
     *                              "ownerName": "J0a Silva",
     *                              "document": "00000",
     *                              "coverageArea": {},
     *                              "address": {}
     *                          }
     *                     }
     *                 )
     *             )
     *         }
     *     ),
     *     @OA\Response(
     *         response="422",
     *         description="Unprocessable Entity",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                     @OA\Property(
     *                         property="success",
     *                         type="boolean",
     *                         description="The request was successfull?"
     *                     ),
     *                     @OA\Property(
     *                         property="message",
     *                         type="string",
     *                         description="The response message"
     *                     ),
     *                     @OA\Property(
     *                         property="data",
     *                         type="array",
     *                         description="The response data",
     *                         @OA\Items
     *                     ),
     *                     example={
     *                         "success": false,
     *                          "message": "error message",
     *                          "data": {}
     *                     }
     *                 )
     *             )
     *         }
     *     ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     * )
     */

    /**
     *
     * Find Nearest partner by given coordinates lat and long.
     *
     * @return JsonResponse
     */
    public function find(Request $request)
    {
        try {
            $httpCode = 200;

            $validator = Validator::make($request->all(), [
                'lat' => 'required|numeric',
                'long' => 'required|numeric',
            ]);

            $validator->validate();

            $partner = $this->partner->findNearestPartner($request->lat, $request->long);

            $data = $this->formatResponse(true, $partner, 'ok');
        } catch (ModelNotFoundException | ValidationException $e) {
            $httpCode = 422;
            $data = $this->formatResponse(false, [], json_encode($e->errors()));
            Log::error(__CLASS__ . '/' . __FUNCTION__ . ':' . __LINE__, ['message' => $e->getMessage()]);
        }
        return $this->sendResponse($data, $httpCode);
    }
}
