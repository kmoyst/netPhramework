<?php

namespace netPhramework\exceptions;

use netPhramework\core\Exception;
use netPhramework\responding\ResponseCode;

class NotFound extends Exception
{
	protected string $friendlyMessage = "Oops! Resource not found.";

    public function __construct(string $message = "")
    {
        parent::__construct($message, ResponseCode::NOT_FOUND);
    }

    public function getTitle(): string
    {
        return 'NOT FOUND';
    }
}