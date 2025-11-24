<?php

namespace App\Http\Requests;

use App\Http\Controllers\Controller;
use App\Utils\JsonResponseAPI;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class SmsRequest extends BaseFormRequest
{
    public function __construct(protected Controller $controller)
    {
        parent::__construct();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        if(in_array(basename($this->url()), ['send'])) {
            # validate header
            if(!$this->hasHeader('Content-Type') || $this->header('Content-Type') !== 'application/json')
                throw new HttpResponseException(JsonResponseAPI::errorResponse( 'Include Content-Type and set the value to: application/json in your header.', ResponseAlias::HTTP_BAD_REQUEST));
        }
        switch (basename($this->url())) {
            case "send": return $this->sendValidation();
        }
    }

    /**
     * @return string[]
     */
    private function sendValidation(): array
    {
        return [
            'recipient' => 'nullable|string|max:20|regex:/^\+?[1-9]\d{1,14}$/',
            'message' => 'nullable|string',
            'is_scheduled' => 'required|integer|in:0,1',
            'scheduled_at' => $this->is_scheduled ? 'required|date|after:now' : 'nullable',
        ];
    }
}
