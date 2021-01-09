<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BaseController extends Controller
{
    /**
     * Format data for response to send on json
     */
    private function format($success, $data, $message, $status)
    {
        return response()->json([
            'success' => $success,
            'data' => $data,
            'message' => $message
        ], $status);
    }

    /**
     * Format and Send a success response
     */
    protected function successResponse($data, $message, $status = 200)
    {
        return $this->format(true, $data, $message, $status);
    }

    /**
     * Format and send a errors response
     */
    protected function errorsResponse($data, $message, $status = 400)
    {
        return $this->format(false, $data, $message, $status);
    }
}
