<?php

namespace netPhramework\http;

use netPhramework\common\Variables;
use netPhramework\core\Environment;
use netPhramework\routing\Location;
use netPhramework\routing\MutablePath;

class LocationFromHttpInput extends Location
{
	protected(set) MutablePath $path {get{
		if(!isset($this->path))
		{
			$this->path = new MutablePath();
			$this->path->append(new PathFromUri($this->environment->uri));
		}
		return $this->path;
	}}

	protected(set) Variables $parameters {
		get{
			if(!isset($this->parameters))
				$this->parameters =
					new VariablesFromUri($this->environment->uri)->get();
			return $this->parameters;
		}
		set{}
	}

	public function __construct(protected readonly Environment $environment) {}

	public function __clone():void
	{
		if(isset($this->path))
			$this->path = clone $this->path;
		if(isset($this->parameters))
			$this->parameters = clone $this->parameters;
	}
}