<?php

namespace netPhramework\routing;

use netPhramework\common\Variables;

class LocationFromUri extends Location
{
	protected(set) Path $path {get{
		if(!isset($this->path))
		{
			$this->path = new Path();
			$this->path->appendPath(new PathFromUri($this->uri));
		}
		return $this->path;
	}}

	protected(set) Variables $parameters {get{
		if(!isset($this->parameters))
			$this->parameters =
				new VariablesFromUri($this->uri)->get();
		return $this->parameters;
	}}

	public function __construct(private readonly string $uri) {}
}