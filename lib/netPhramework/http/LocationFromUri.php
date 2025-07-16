<?php

namespace netPhramework\http;

use netPhramework\common\Variables;
use netPhramework\routing\Location;
use netPhramework\routing\MutablePath;

class LocationFromUri extends Location
{
	protected(set) MutablePath $path {get{
		if(!isset($this->path))
		{
			$this->path = new MutablePath();
			$this->path->append(new PathFromUri($this->uri));
		}
		return $this->path;
	}}

	protected(set) Variables $parameters {
		get{
			if(!isset($this->parameters))
				$this->parameters =
					new VariablesFromUri($this->uri)->get();
			return $this->parameters;
		}
	}

	public function __construct(private string $uri) {}

	public function __clone():void
	{
		if(isset($this->path))
			$this->path = clone $this->path;
		if(isset($this->parameters))
			$this->parameters = clone $this->parameters;
	}
}