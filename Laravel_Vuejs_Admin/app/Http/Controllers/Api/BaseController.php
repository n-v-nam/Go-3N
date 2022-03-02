<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Request;

class BaseController extends Controller
{
    /**
     * success response method.
     *
     * @param $result
     * @param $message
     *
     * @return \Illuminate\Http\Response
     */
    public function sendResponse($result, $message)
    {
        $response = [
            'success' => true,
            'data'    => $result,
            'message' => $message,
        ];


        return response()->json($response, 200);
    }


    /**
     * return error response.
     *
     * @param $error
     * @param  array  $errorMessages
     * @param  int  $code
     *
     * @return \Illuminate\Http\Response
     */
    public function sendError($message = 'Not Found', $code = 404)
    {
        $response = [
            'success' => false,
            'message' => $message,
        ];

        return response()->json($response, $code);
    }


    /**
     * return Unauthorized response.
     *
     * @param $error
     * @param  int  $code
     *
     * @return \Illuminate\Http\Response
     */
    public function unauthorizedResponse($error = 'Forbidden', $code = 403)
    {
        $response = [
            'success' => false,
            'message' => $error,
        ];

        return response()->json($response, $code);
    }

    public function failValidator(Validator $validator, $code = 400)
    {
        $response = [
            'success' => false,
            'message' => $validator->errors()
        ];

        return response()->json($response, $code);
    }

    public function errorInternal($message = 'Internal Error', $code = 500)
    {
        $response = [
            'success' => false,
            'message' => $message
        ];

        return response()->json($response, $code);
    }

    public function withSuccessMessage($message, $code = 200)
    {
        $response = [
            'success' => true,
            'message' => $message
        ];

        return response()->json($response, $code);
    }

    public function badRequest($message = 'Wrong Request', $code = 400)
    {
        $response = [
            'success' => false,
            'message' => $message
        ];

        return response()->json($response, $code);
    }
}
