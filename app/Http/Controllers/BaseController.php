<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @SWG\Swagger(host=API_HOST)
 */
/**
 * @OA\Info(
 *     description="Zé Delivery Partners API Code Challange",
 *     version="0.0.0.",
 *     title="Zé Delivery Partners API",
 *     @OA\Contact(
 *         email="clayson.capo@gmail.com"
 *     )
 * )
 */
/**
 * @OA\Server(url="http://127.0.0.1:8888/api")
 */

/**
 * @OA\Tag(
 *     name="Partners",
 *     description="Create, Find and Search Zé Delivery partners endpoints",
 * )
 */
class BaseController extends Controller
{


    /**
     * response method.
     *
     * @return JsonResponse
     */
    public function sendResponse(array $response, int $status): JsonResponse
    {
        return response()->json($response, $status);
    }

    /**
     * decode json.
     *
     * @return array
     */

    public function decodeRequest(Request $request): array
    {
        return json_decode($request->getContent(), true) ?? [];
    }

    /**
     * decode json.
     *
     * @return array
     */

    public function formatResponse(bool $success, array $data, string $message): array
    {
        return [
            'success' => $success,
            'data' => $data,
            'message' => $message,
        ];
    }

}
