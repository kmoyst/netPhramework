<?php

namespace netPhramework\locating;

use netPhramework\common\Variables;

class Location extends ReadableLocation
{
	protected MutablePath $path;
	protected Variables $parameters;

	public function getPath(): MutablePath
	{
		if(!isset($this->path))
			$this->path = new MutablePath();
		return $this->path;
	}

	public function setPath(MutablePath $path): self
	{
		$this->path = $path;
		return $this;
	}

	public function getParameters(): Variables
	{
		if(!isset($this->parameters))
			$this->parameters = new Variables();
		return $this->parameters;
	}
}