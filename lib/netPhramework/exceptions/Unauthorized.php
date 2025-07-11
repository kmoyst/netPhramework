<?php

namespace netPhramework\exceptions;

use netPhramework\responding\ResponseCode;

class Unauthorized extends Exception
{
    public function __construct(string $message = "")
    {
        parent::__construct($message, ResponseCode::UNAUTHORIZED);
    }
}