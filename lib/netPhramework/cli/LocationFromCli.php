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
			$this->path = new Path();
			$this->path->appendPath(new PathFromCli());
		}
		return $this->path;
	}}


	protected(set) Variables $parameters {get{
		if(!isset($this->parameters))
		{
			$this->parameters = new Variables();
		}
		return $this->parameters;
	}}
}