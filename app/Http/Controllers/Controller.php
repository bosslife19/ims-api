<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Symfony\Component\HttpFoundation\Response;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * Return a success response with or without data.
     *
     * @param  mixed  $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function successResponse(array $data, string $message = '', int $statusCode = Response::HTTP_OK): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'status' => true,
            'data' => empty($data) ? null : $data,
            'message' => $message,
        ], $statusCode);
    }
}
