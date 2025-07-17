<?php

namespace netPhramework\routing;

use netPhramework\exceptions\InvalidUri;
use netPhramework\exceptions\PathException;

class PathFromUri extends Path
{
	private bool $parsed = false;

	public function __construct(private readonly string $uri) {}

	/**
	 * @return string|null
	 * @throws InvalidUri
	 */
	public function getName(): ?string
	{
		$this->parse();
		return parent::getName();
	}

	/**
	 * @return Path|null
	 * @throws InvalidUri
	 */
	public function getNext(): ?Path
	{
		$this->parse();
		return parent::getNext();
	}

	/**
	 * @param Path $tail
	 * @return Path
	 * @throws InvalidUri
	 * @throws PathException
	 */
	public function appendPath(Path $tail): Path
	{
		$this->parse();
		return parent::appendPath($tail);
	}

	/**
	 * @return void
	 * @throws InvalidUri
	 */
	private function parse():void
	{
		if($this->parsed) return;
		if(!preg_match('|^/([^?]*)|', $this->uri, $matches))
			throw new InvalidUri("Invalid Uri: $this->uri");
		$names = explode('/', $matches[1]);
		$this->setName(array_shift($names));
		if(count($names) >= 1)
		$this->setNext(new PathFromArray($names));
		$this->parsed = true;
	}
}