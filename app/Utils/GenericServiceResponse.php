<?php
namespace App\Utils;

use App\Enums\ServiceResponseMessage;
use App\Traits\DTOTrait;

class GenericServiceResponse
{
    use DTOTrait;

    /**
     * @param bool $status
     * @param string|null $action
     * @param string|null $message
     * @param mixed $data
     */
    public function __construct(
        public bool $status = false,
        public ?string $message = 'Sorry!, something went wrong.',
        public mixed $data = null,
        public string|null $action = null,
    ) { }

}
