<?php

namespace netPhramework\exchange;

use netPhramework\common\FileFinder;
use netPhramework\rendering\Encodable;
use netPhramework\rendering\Encoder;
use netPhramework\rendering\Wrappable;
use netPhramework\rendering\Wrapper;
use netPhramework\transferring\File;

readonly class Responder
{
	public function __construct
	(
		public Encoder $encoder = new Encoder(),
		public Wrapper $wrapper = new Wrapper(),
		public FileFinder $templateFinder = new FileFinder()
	)
	{}

	private function prepare():self
	{
		$this->encoder->setTemplateFinder($this->templateFinder);
		return $this;
	}

	public function present(Wrappable $content, ResponseCode $code): void
	{
		http_response_code($code->value);
		echo $this->wrapper->wrap($content)->encode($this->prepare()->encoder);
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