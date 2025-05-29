<?php

namespace netPhramework\responding;

use netPhramework\db\transferring\File;
use netPhramework\rendering\Encodable;
use netPhramework\rendering\Encoder;

readonly class Responder
{
	public function __construct(private Encoder $encoder) {}

	public function present(Encodable $content, ResponseCode $code): void
	{
		http_response_code($code->value);
		echo $content->encode($this->encoder);
	}

	public function redirect(Encodable $content, ResponseCode $code): void
	{
		http_response_code($code->value);
		header("Location: " . $content->encode($this->encoder));
	}

	public function transfer(File $file, ResponseCode $code): void
	{
		http_response_code($code->value);
		header('Content-Type: '.$file->getFileType());
		header('Content-Disposition: attachment; filename="'.$file->getFileName().'"');
		header("Content-Length: " . filesize($file->getStoredPath()));
		$buffer = fopen($file->getStoredPath(), "r");
		fpassthru($buffer);
		fclose($buffer);
	}
}