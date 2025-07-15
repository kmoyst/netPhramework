<?php

namespace netPhramework\cli;

use netPhramework\routing\Location;
use netPhramework\routing\MutablePath;

class LocationFromCli extends Location
{
	public MutablePath $path {get{
		if(!isset($this->path))
		{
			$this->path = new MutablePath();
			$this->path->append(new PathFromCli());
		}
		return $this->path;
	}}
}