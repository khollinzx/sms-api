<?php

namespace App\Repositories;

use App\Abstractions\AbstractClasses\BaseRepositoryAbstract;
use App\Enums\ServiceResponseMessage;
use App\Models\SMS;
use App\Services\SmsService;
use App\Utils\GenericServiceResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SMSRepository extends BaseRepositoryAbstract
{

    /**
     * @var string
     */
    protected string $databaseTableName = 's_m_s';
    public string $name = 's_m_s';
    protected GenericServiceResponse $response;
    protected SmsService $smsService;

    /**
     * @param SMS $model
     */
    public function __construct(SMS $model)
    {
        parent::__construct($model, $this->databaseTableName);
        $this->response = new GenericServiceResponse(false, ServiceResponseMessage::ERROR_OCCURRED);
        $this->smsService = new SmsService();
    }

    /**
     * @param array $validated
     * @return GenericServiceResponse
     */
    public function sendSms(array $validated) : GenericServiceResponse
    {
        try {
            /** @var SMS $data */
            $sms = SMS::repo()->createModel([
                'recipient' => $validated['recipient'],
                'message' => $validated['message'],
                'is_scheduled' => $validated['is_scheduled'],
                'scheduled_at' => $validated['is_scheduled'] ? $validated['scheduled_at'] : null,
                'status' => $validated['is_scheduled'] ? 'queued' : 'pending',
            ]);

            if(!$sms->is_scheduled)
                $this->smsService->sendSms($sms);

            $this->response->status = true;
            $this->response->message = $sms->is_scheduled ? ServiceResponseMessage::SMS_QUEUED : ServiceResponseMessage::SMS_SENT;
            $this->response->data = $sms;
            return $this->response;
        } catch (\Exception $exception) {
            Log::error($exception);
            return $this->response;
        }
    }

    /**
     * @param Request $request
     * @return GenericServiceResponse
     */
    public function getSmsMessage(Request $request) : GenericServiceResponse
    {
        try {
            $query = SMS::query()->with($this->model->relationships);
            $query->orderByDesc('id');

            $data = $query->paginate($request->get('page_size', 10));

            $this->response->status = true;
            $this->response->message = ServiceResponseMessage::RETRIEVED_DATA_SUCCESSFULLY;
            $this->response->data = $data;
            return $this->response;
        } catch (\Exception $exception) {
            Log::error($exception);
            return $this->response;
        }
    }

    /**
     * @param string $sms_public_id
     * @return GenericServiceResponse
     */
    public function viewSmsMessage(string $sms_public_id) : GenericServiceResponse
    {
        try {
            $data = SMS::repo()->findSingleByWhereClause(['public_id' => $sms_public_id]);
            if(!$data) {
                $this->response->message = ServiceResponseMessage::CAN_NOT_RETRIEVE_RECORD;
                return $this->response;
            }

            $this->response->status = true;
            $this->response->message = ServiceResponseMessage::RETRIEVED_DATA_SUCCESSFULLY;
            $this->response->data = $data;
            return $this->response;
        } catch (\Exception $exception) {
            Log::error($exception);
            return $this->response;
        }
    }

    /**
     * @param string $sms_public_id
     * @return GenericServiceResponse
     */
    public function cancelSmsMessage(string $sms_public_id) : GenericServiceResponse
    {
        try {
            $sms = SMS::repo()->findSingleByWhereClause(['public_id' => $sms_public_id]);
            if(!$sms) {
                $this->response->message = ServiceResponseMessage::CAN_NOT_RETRIEVE_RECORD;
                return $this->response;
            }
            if(!in_array($sms->status, ['queued', 'pending'])) {
                $this->response->message = ServiceResponseMessage::ACTION_DENIED;
                return $this->response;
            }
            $data = SMS::repo()->updateByIdAndGetBackRecord($sms->id, ['status' => 'cancelled']);

            $this->response->status = true;
            $this->response->message = ServiceResponseMessage::ACTION_SUCCESSFUL;
            $this->response->data = $data;
            return $this->response;
        } catch (\Exception $exception) {
            Log::error($exception);
            return $this->response;
        }
    }
}
