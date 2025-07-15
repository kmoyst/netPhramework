<?php

namespace netPhramework\http;

use netPhramework\common\Variables;
use netPhramework\exchange\Request;
use netPhramework\routing\MutablePath;

class HttpRequest extends Request
{
	protected(set) MutablePath $path {get{
		if(!isset($this->path))
		{
			$this->path = new MutablePath();
			$this->path->append(new PathFromUri($this->input->uri));
		}
		return $this->path;
	}set{}}

	protected(set) Variables $parameters {get{
		if(!isset($this->parameters))
		{
			$this->parameters = new Variables();
			if(!$this->input->hasPostParameters())
				$this->parameters->merge($this->input->getParameters ?? []);
			else
				$this->parameters->merge($this->input->postParameters);
		}
		return $this->parameters;
	}set{}}

	public function __construct(protected HttpInput $input = new HttpInput()) {}

	public function isModificationRequest(): bool
	{
		return $this->input->hasPostParameters();
	}
}
