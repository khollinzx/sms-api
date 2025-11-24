<?php
namespace App\Enums;


/**
 * return strings
 */
enum ServiceResponseMessage: string
{
    const RETRIEVED_DATA_SUCCESSFULLY = 'Data was retrieved Successfully!';
    const CAN_NOT_RETRIEVE_RECORD = 'No results found!';
    const ACTION_DENIED = 'Sorry!, you cannot cancel a message that has been sent or cancelled.';
    const ACTION_SUCCESSFUL = 'Performed action successfully!.';
    const SMS_QUEUED = 'Your sms have been queued';
    const SMS_SENT = 'Your sms have been sent';
    const ERROR_OCCURRED = 'Error occurred while performing action!';
}
