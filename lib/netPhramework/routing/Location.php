<?php

namespace netPhramework\routing;

use netPhramework\common\Variables;

class Location extends ReadableLocation
{
	protected(set) Path $path {get{
		if(!isset($this->path))
			$this->path = new Path();
		return $this->path;
	}}

	protected(set) Variables $parameters {get{
		if(!isset($this->parameters))
			$this->parameters = new Variables();
		return $this->parameters;
	}}

	public function getPath(): Path
	{
		return $this->path;
	}

	public function setPath(Path $path): self
	{
		$this->path = $path;
		return $this;
	}

	public function getParameters(): Variables
	{
		return $this->parameters;
	}

	public function setParameters(Variables $parameters):self
	{
		$this->parameters = $parameters;
		return $this;
	}

	public function __clone():void
	{
		$this->path = clone $this->path;
		$this->parameters = clone $this->parameters;
	}
}