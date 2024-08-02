<?php

namespace App\Traits;

trait ResponseTrait
{
    protected function successResponse($data, $message="Successfully", $status=200){
        return response()->json([
            'message' => $message,
            'data' => $data,
        ], $status);
    }

    protected function errorResponse($data, $message="Not Successfully", $status=400){
        return response()->json([
            'message' => $message,
            $data,
        ], $status);
    }
}
