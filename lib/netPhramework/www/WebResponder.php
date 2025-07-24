<?php

namespace netPhramework\www;

use netPhramework\common\FileFinder;
use netPhramework\core\Application;
use netPhramework\exchange\Responder;
use netPhramework\exchange\ResponseCode;
use netPhramework\exchange\Services;
use netPhramework\rendering\Encoder;
use netPhramework\rendering\Wrappable;
use netPhramework\rendering\Wrapper;
use netPhramework\routing\Location;
use netPhramework\transferring\File;

class WebResponder implements Responder
{
	public string $siteAddress;
	public Application $application;
	public Services $services;
	public Encoder $encoder;
	public Wrapper $wrapper;
	public FileFinder $templateFinder;

	public function setEncoder(Encoder $encoder): self
	{
		$this->encoder = $encoder;
		return $this;
	}

	public function setWrapper(Wrapper $wrapper): self
	{
		$this->wrapper = $wrapper;
		return $this;
	}

	public function setTemplateFinder(FileFinder $templateFinder): self
	{
		$this->templateFinder = $templateFinder;
		return $this;
	}

	public function setSiteAddress(string $siteAddress): self
	{
		$this->siteAddress = $siteAddress;
		return $this;
	}

	public function setApplication(Application $application): self
	{
		$this->application = $application;
		return $this;
	}

	public function setServices(Services $services): self
	{
		$this->services = $services;
		return $this;
	}

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
		if(ob_get_length() > 0) ob_end_clean();
		readfile($sp);
	}
}