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
			if(!preg_match('|^/([^?]*)|', $this->uri, $matches))
				throw new InvalidUri("Invalid Uri: $this->uri");
			$names = explode('/', $matches[1]);
			$this->path  = new MutablePath(array_shift($names));
			$this->traverseArray($this->path, $names);
		}
        return $this->path;
    }

	/**
	 * @param MutablePath $path
	 * @param array $names
	 * @return void
	 * @throws PathException
	 */
    private function traverseArray(MutablePath $path, array $names):void
    {
		if(count($names) === 0) return;
		$path->setNext(array_shift($names));
		$this->traverseArray($path->getNext(), $names);
    }

	public function getParameters():Variables
	{
		if(!isset($this->parameters))
		{
			$this->parameters = new Variables();
			if(preg_match('|\?(.+)$|', $this->uri, $matches))
			{
				parse_str($matches[1], $arr);
				$this->parameters->merge($arr);
			}
		}
		return $this->parameters;
	}

	public function __clone():void
	{
		unset($this->path);
		unset($this->parameters);
	}
}