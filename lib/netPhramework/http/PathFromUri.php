<?php

namespace netPhramework\http;

use netPhramework\exceptions\InvalidUri;
use netPhramework\routing\Path;
use netPhramework\routing\PathFromArray;

class PathFromUri extends Path
{
	protected ?string $name = null;
	protected ?Path $next = null;

	private ?Path $delegate = null;

	public function __construct(private readonly string $uri) {}

	/**
	 * @return string|null
	 * @throws InvalidUri
	 */
	public function getName(): ?string
	{
		$this->parse();
		return $this->delegate->getName();
	}

	/**
	 * @return Path|null
	 * @throws InvalidUri
	 */
	public function getNext(): ?Path
	{
		$this->parse();
		return $this->delegate->getNext();
	}

	public function setName(?string $name): Path
	{
		$this->parse();
		$this->delegate->setName($name);
		return $this;
	}

	public function setNext(?Path $next): Path
	{
		$this->parse();
		$this->delegate->setNext($next);
		return $this;
	}

	public function appendPath(Path $tail): Path
	{
		$this->parse();
		$this->delegate->appendPath($tail);
		return $this;
	}


	/**
	 * @throws InvalidUri
	 */
	private function parse():void
	{
		if(isset($this->delegate)) return;
		if(!preg_match('|^/([^?]*)|', $this->uri, $matches))
			throw new InvalidUri("Invalid Uri: $this->uri");
		$names = explode('/', $matches[1]);
		$this->delegate = new PathFromArray($names);
	}
}