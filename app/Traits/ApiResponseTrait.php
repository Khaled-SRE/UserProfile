<?php

namespace App\Traits;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Pagination\AbstractPaginator;

trait ApiResponseTrait
{
    /**
     * Unified success response.
     */
    protected function successResponse($data = null, string $messageKey = 'response.success', int $code = 200): array
    {
        $response = [
            'code' => $code,
            'status' => true,
            'message' => __($messageKey),
        ];

        // Data section
        if ($data instanceof AbstractPaginator) {
            $response['data'] = $data->items();
            $response['meta'] = [
                'current_page' => $data->currentPage(),
                'last_page' => $data->lastPage(),
                'per_page' => $data->perPage(),
                'total' => $data->total(),
                'next_page_url' => $data->nextPageUrl(),
                'prev_page_url' => $data->previousPageUrl(),
            ];
        } elseif ($data instanceof JsonResource) {
            $response['data'] = $data->resolve(request());
        } elseif (! is_null($data)) {
            $response['data'] = $data;
        }

        return $response;
    }

    /**
     * Unified error response with support for validation messages.
     */
    protected function errorResponse(string $messageKey = 'response.error', $errors = null, int $code = 442): array
    {

        $response = [
            'code' => $code,
            'status' => false,
            'message' => __($messageKey),
        ];

        if (! is_null($errors)) {
            $response['errors'] = is_array($errors)
                ? $errors
                : (method_exists($errors, 'errors')
                    ? $errors->errors()
                    : (array) $errors);
        }

        return $response;
    }
}
