<?php

namespace App\Traits;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Pagination\LengthAwarePaginator;

trait HttpResponseTrait
{
    /**
     * Success response
     **/

    public function successResponse ($message, $data, $code = 200) {

        if ($data instanceof JsonResource && $data->resource instanceof LengthAwarePaginator) {
            $data = $data->response()->getData();
        }

        return response()->json([
            "message" => "Successfully $message.",
            "status" => "passed",
            "data" => $data
        ], $code);
    }

    /**
     * Fail Response
     **/
    public function failResponse ($message, $data, $code = 500) {
        return response()->json([
            "message" => $message,
            "status" => "Failed",
            "data" => $data
        ], $code);
    }
}
