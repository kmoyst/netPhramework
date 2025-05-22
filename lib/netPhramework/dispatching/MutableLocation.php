<?php

namespace netPhramework\dispatching;

use netPhramework\common\Variables;

class MutableLocation implements Relocatable, Location
{
	public function __construct(private readonly Path $path,
								private readonly Variables $parameters) {}

	public function getPath(): Path
	{
		return $this->path;
	}

	public function getParameters(): Variables
	{
		return $this->parameters;
	}
}