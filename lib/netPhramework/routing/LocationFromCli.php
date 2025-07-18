<?php

namespace netPhramework\routing;

use netPhramework\common\Variables;

class LocationFromCli extends Location
{
	public Path $path {get{
		if(!isset($this->path))
		{
			$this->path = new PathFromCli();
		}
		return $this->path;
	}}


	public Variables $parameters {get{
		if(!isset($this->parameters))
		{
			$this->parameters = new Variables();
		}
		return $this->parameters;
	}}
}