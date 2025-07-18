<?php

namespace netPhramework\routing;

use netPhramework\common\Variables;
use netPhramework\http\HttpInput;

class LocationFromHttpInput extends Location
{
	protected(set) Path $path {get{
		if(!isset($this->path))
		{
			$this->path = new PathFromUri($this->input->uri);
		}
		return $this->path;
	}}

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
	}}

	public function __construct(private readonly HttpInput $input) {}
}