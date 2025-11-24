<?php

namespace App\Http\Controllers;

use App\Http\Requests\SmsRequest;
use App\Http\Resources\DataCollection;
use App\Models\SMS;
use App\Utils\JsonResponseAPI;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SMSController extends Controller
{
    /**
     * @param SmsRequest $request
     * @return JsonResponse
     */
    public function send(SmsRequest $request): JsonResponse
    {
        $validated = $request->validated();
        try {
            $response = SMS::repo()->sendSms($validated);
            if(!$response->status) return JsonResponseAPI::errorResponse($response->message, JsonResponseAPI::HTTP_BAD_REQUEST);
            return JsonResponseAPI::successResponse($response->message, $response->data, JsonResponseAPI::HTTP_OK);
        } catch (\Exception $exception) {
            Log::error($exception);
            return JsonResponseAPI::errorResponse("Internal server error.", JsonResponseAPI::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function list(Request $request): JsonResponse
    {
        try {
            $response = SMS::repo()->getSmsMessage($request);
            if(!$response->status) return JsonResponseAPI::errorResponse($response->message, JsonResponseAPI::HTTP_BAD_REQUEST);
            return JsonResponseAPI::successResponse($response->message,(new DataCollection($response->data)), JsonResponseAPI::HTTP_OK);
        } catch (\Exception $exception) {
            Log::error($exception);
            return JsonResponseAPI::errorResponse("Internal server error.", JsonResponseAPI::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @param string $id
     * @return JsonResponse
     */
    public function view(string $id): JsonResponse
    {
        try {
            $response = SMS::repo()->viewSmsMessage($id);
            if(!$response->status) return JsonResponseAPI::errorResponse($response->message, JsonResponseAPI::HTTP_BAD_REQUEST);
            return JsonResponseAPI::successResponse($response->message, $response->data, JsonResponseAPI::HTTP_OK);
        } catch (\Exception $exception) {
            Log::error($exception);
            return JsonResponseAPI::errorResponse("Internal server error.", JsonResponseAPI::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @param string $id
     * @return JsonResponse
     */
    public function cancel(string $id): JsonResponse
    {
        try {
            $response = SMS::repo()->cancelSmsMessage($id);
            if(!$response->status) return JsonResponseAPI::errorResponse($response->message, JsonResponseAPI::HTTP_BAD_REQUEST);
            return JsonResponseAPI::successResponse($response->message, $response->data, JsonResponseAPI::HTTP_OK);
        } catch (\Exception $exception) {
            Log::error($exception);
            return JsonResponseAPI::errorResponse("Internal server error.", JsonResponseAPI::HTTP_BAD_REQUEST);
        }
    }
}
