<?php

namespace netPhramework\cli;

use netPhramework\common\Variables;
use netPhramework\routing\Location;
use netPhramework\routing\MutablePath;

class LocationFromCli extends Location
{
	protected(set) MutablePath $path {get{
		if(!isset($this->path))
		{
			$this->path = new MutablePath();
			$this->path->append(new PathFromCli());
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