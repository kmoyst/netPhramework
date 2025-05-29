<?php

namespace netPhramework\transferring;

use netPhramework\core\Responder;
use netPhramework\core\Response;
use netPhramework\core\ResponseCode;

class Download implements Response
{
	private File $file;
	private ResponseCode $code;

	public function __construct(File $file,
								ResponseCode $code = ResponseCode::OK)
	{
		$this->file = $file;
		$this->code = $code;
	}

	public function deliver(Responder $responder): void
	{
		$responder->transferFile($this->file, $this->code);
	}
}