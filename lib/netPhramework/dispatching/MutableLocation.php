<?php

namespace netPhramework\dispatching;

use netPhramework\common\Variables;

readonly class MutableLocation implements Location
{
	public function __construct(private Path $path,
								private Variables $parameters) {}

	public function getPath(): Path
	{
		return $this->path;
	}

	public function getParameters(): Variables
	{
		return $this->parameters;
	}
}