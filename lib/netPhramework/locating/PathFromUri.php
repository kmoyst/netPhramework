<?php

namespace netPhramework\locating;

use netPhramework\exceptions\InvalidUri;

class PathFromUri extends Path
{
	private Path $path;

	public function __construct(private readonly string $uri) {}

	/**
	 * @return string|null
	 * @throws InvalidUri
	 */
	public function getName(): ?string
	{
		$this->parse();
		return $this->path->getName();
	}

	/**
	 * @return Path|null
	 * @throws InvalidUri
	 */
	public function getNext(): ?Path
	{
		$this->parse();
		return $this->path->getNext();
	}

	/**
	 * @return self
	 * @throws InvalidUri
	 */
	public function parse():self
	{
		if(isset($this->path)) return $this;
		if(!preg_match('|^/([^?]*)|', $this->uri, $matches))
			throw new InvalidUri("Invalid Uri: $this->uri");
		$names = explode('/', $matches[1]);
		$this->path = new PathFromArray($names);
		return $this;
	}
}