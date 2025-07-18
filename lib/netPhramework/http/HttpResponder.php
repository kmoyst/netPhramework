<?php

namespace netPhramework\http;

use netPhramework\common\FileFinder;
use netPhramework\core\Application;
use netPhramework\core\Environment;
use netPhramework\exchange\Responder;
use netPhramework\exchange\ResponseCode;
use netPhramework\exchange\Services;
use netPhramework\rendering\Encoder;
use netPhramework\rendering\HtmlEncoder;
use netPhramework\rendering\Wrappable;
use netPhramework\rendering\Wrapper;
use netPhramework\routing\Location;
use netPhramework\transferring\File;

class HttpResponder implements Responder
{
	public Environment $environment;
	public Application $application;
	public Services $services;

	public function __construct
	(
		public Encoder $encoder = new HtmlEncoder(),
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

	public function redirect(Location $location, ResponseCode $code): void
	{
		http_response_code($code->value);
		header("Location: " . $location->encode($this->encoder));
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