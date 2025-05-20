<?php

namespace netPhramework\exceptions;

use netPhramework\responding\ResponseCode;

class NoContent extends ResponseException
{
    public function __construct(string $message = "")
    {
        parent::__construct($message, ResponseCode::NO_CONTENT);
    }
}