<?php

namespace netPhramework\core;

use netPhramework\db\transferring\File;

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