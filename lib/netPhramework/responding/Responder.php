<?php

namespace netPhramework\responding;

use netPhramework\core\File;
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
		$ft = $file->getFileType();
		$fn = $file->getFileName();
		$sp = $file->getStoredPath();
		http_response_code($code->value);
		header("Content-Type: $ft");
		header("Content-Disposition: attachment; filename=\"$fn\"");
		header("Content-Length: " . filesize($sp));
		ob_end_clean();
		flush();
		readfile($sp);
	}
}