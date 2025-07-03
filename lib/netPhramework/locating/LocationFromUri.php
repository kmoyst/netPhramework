<?php

namespace netPhramework\locating;

use netPhramework\common\Variables;
use netPhramework\exceptions\InvalidUri;
use netPhramework\exceptions\PathException;

class LocationFromUri extends Location implements MutableLocation
{
	private MutablePath $path;

	public function __construct(
		private readonly string $uri, private ?Variables $parameters = null) {}

	public function setParameters(Variables|array|null $parameters): self
	{
		if(is_array($parameters))
			$this->parameters = new Variables()->merge($parameters);
		else
			$this->parameters = $parameters;
		return $this;
	}

	/**
	 * @return MutablePath
	 * @throws InvalidUri
	 * @throws PathException
	 */
	public function getPath():MutablePath
    {
		if(!isset($this->path))
		{
			$this->path = new MutablePath();
			$this->path->append(new PathFromUri($this->uri)->parse());
		}
        return $this->path;
    }

	public function getParameters():Variables
	{
		if(!isset($this->parameters))
			$this->parameters = new VariablesFromUri($this->uri)->parse();
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