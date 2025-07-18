<?php

namespace netPhramework\routing;

use netPhramework\common\Variables;

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