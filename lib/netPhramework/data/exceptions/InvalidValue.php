<?php

namespace netPhramework\data\exceptions;

use netPhramework\exchange\ResponseCode;

class InvalidValue extends RecordSaveException
{
	public function __construct(string $message = "")
	{
		parent::__construct($message, ResponseCode::UNPROCESSABLE_CONTENT);
	}

}