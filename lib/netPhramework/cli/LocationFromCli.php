<?php

namespace netPhramework\cli;

use netPhramework\common\Variables;
use netPhramework\routing\Location;
use netPhramework\routing\Path;

class LocationFromCli extends Location
{
	protected(set) Path $path {get{
		if(!isset($this->path))
		{
			$this->path = new PathFromCli();
		}
		return $this->path;
	}set{}}


	protected(set) Variables $parameters {get{
		if(!isset($this->parameters))
		{
			$this->parameters = new Variables();
		}
		return $this->parameters;
	}set{}}
}