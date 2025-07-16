<?php

namespace netPhramework\http;

use netPhramework\common\Variables;
use netPhramework\routing\Location;
use netPhramework\routing\MutablePath;

class LocationFromHttpInput extends Location
{
	protected(set) MutablePath $path {get{
		if(!isset($this->path))
		{
			$this->path = new MutablePath();
			$this->path->append(new PathFromUri($this->input->uri));
		}
		return $this->path;
	}}

	protected(set) Variables $parameters {
		get{
			if(!isset($this->parameters))
			{
				$this->parameters = new Variables();
				if(!$this->input->hasPostParameters())
					$this->parameters->merge($this->input->getParameters ?? []);
				else
					$this->parameters->merge($this->input->postParameters);
			}
			return $this->parameters;
		}
		set{}
	}

	public function __construct(private HttpInput $input) {}

	public function __clone():void
	{
		if(isset($this->path))
			$this->path = clone $this->path;
		if(isset($this->parameters))
			$this->parameters = clone $this->parameters;
	}
}