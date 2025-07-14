<?php

namespace netPhramework\routing;

use netPhramework\common\Variables;
use netPhramework\exceptions\PathException;

class LocationFromUri extends Location
{
	public function __construct(private readonly string $uri) {}

	/**
	 * @return $this
	 * @throws PathException
	 */
	public function initialize():self
	{
		// to allow access to a pre-initialized public property
		$this->getPath();
		$this->getParameters();
		return $this;
	}

	/**
	 * @return MutablePath
	 * @throws PathException
	 */
	public function getPath():MutablePath
    {
		if(!isset($this->path))
		{
			$this->path = new MutablePath();
			$this->path->append(new PathFromUri($this->uri));
		}
        return $this->path;
    }

	public function getParameters():Variables
	{
		if(!isset($this->parameters))
			$this->parameters = new VariablesFromUri($this->uri)->get();
		return $this->parameters;
	}

	public function __clone():void
	{
		if(isset($this->path))
			$this->path = clone $this->path;
		if(isset($this->parameters))
			$this->parameters = clone $this->parameters;
	}
}