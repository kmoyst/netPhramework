<?php

namespace netPhramework\routing;

use netPhramework\common\Variables;

class LocationFromCli extends Location
{
	public Path $path {get{
		if(!isset($this->path))
		{
			echo "\n\n!!! Making a new Path FROM CLI!!!\n\n";
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