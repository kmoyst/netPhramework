<?php

namespace netPhramework\db\exceptions;

use netPhramework\responding\ResponseCode;

class InvalidValue extends RecordSaveException
{
	public function __construct(string $message = "")
	{
		parent::__construct($message, ResponseCode::UNPROCESSABLE_CONTENT);
	}

}