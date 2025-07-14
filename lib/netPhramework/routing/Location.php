<?php

namespace netPhramework\routing;

use netPhramework\common\Variables;

class Location extends ReadableLocation
{
	protected(set) MutablePath $path {
		get{
			if(!isset($this->path))
				$this->path = new MutablePath();
			return $this->path;
		}
		set{}}
	protected(set) Variables $parameters {
		get{
			if(!isset($this->parameters))
				$this->parameters = new Variables();
			return $this->parameters;
		}
		set{}}

	public function getPath(): MutablePath
	{
		return $this->path;
	}

	public function setPath(MutablePath $path): self
	{
		$this->path = $path;
		return $this;
	}

	public function getParameters(): Variables
	{
		return $this->parameters;
	}
}