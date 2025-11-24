<?php

namespace App\Services;

use App\Models\SMS;
use Illuminate\Config\Repository;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsService
{
    /**
     * @return Repository|Application|mixed|object|null
     */
    public function getApiKey(): string
    {
        return config('services.sms.api_key');
    }

    /**
     * @return Repository|Application|mixed|object|null
     */
    public function getUrl(): string
    {
        return config('services.sms.api_url');
    }

    /**
     * @return Repository|Application|mixed|object|null
     */
    public function getSender(): string
    {
        return config('services.sms.from_number');
    }

    /**
     * @return array
     */
    public function getHeaders(): array
    {
        return [
            'Authorization'     => 'Basic ' . base64_encode($this->getApiKey()),
        ];
    }

    public function sendSms(SMS $sms): void
    {
        try {
            $payload = [
                'To' => "+234" . ltrim($sms->recipient, "0"),
                'Body' => $sms->message,
                'From' => $this->getSender()
            ];

            Log::info(json_encode($payload, JSON_PRETTY_PRINT));

            $response = Http::asForm()->withHeaders($this->getHeaders())->post($this->getUrl(), $payload)->json();
            Log::info(json_encode($response, JSON_PRETTY_PRINT));

            if (isset($response->status) && in_array($response->status, [400, 404])) {
                SMS::repo()->updateByIdAndGetBackRecord($sms->id, ['status' => 'failed']);
            }

            SMS::repo()->updateByIdAndGetBackRecord($sms->id, ['status' => 'sent', 'sent_at' => now()]);

            Log::info("SMS sent successfully", ['sms_id' => $sms->public_id]);
        } catch (\Exception $e) {
            Log::error("SMS sending failed", ['sms_id' => $sms->public_id, 'error' => $e->getMessage()]);
        }
    }
}
