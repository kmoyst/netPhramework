<?php

namespace netPhramework\transferring;

use netPhramework\exchange\Responder;
use netPhramework\exchange\Response;
use netPhramework\exchange\ResponseCode;

class FileTransfer implements Response
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
		$responder->transfer($this->file, $this->code);
	}
}