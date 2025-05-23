<?php

namespace netPhramework\exceptions;

use netPhramework\core\Exception;
use netPhramework\core\ResponseCode;

class BadRequest extends Exception
{
    public function __construct(string $message = "")
    {
        parent::__construct($message, ResponseCode::BAD_REQUEST);
    }
}