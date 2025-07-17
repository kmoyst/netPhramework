<?php

namespace netPhramework\routing;

use netPhramework\exceptions\InvalidUri;
use netPhramework\exceptions\PathException;

class PathFromUri extends Path
{
	//private ?Path $next;
	private bool $isParsed = false;

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
		//$next = $this->next->getNext();
		//return $next ?: parent::getNext();
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
		parent::appendPath($tail);
		return $this;
	}

	/**
	 * @return void
	 * @throws InvalidUri
	 */
	private function parse():void
	{
		if($this->isParsed) return;
		if(!preg_match('|^/([^?]*)|', $this->uri, $matches))
			throw new InvalidUri("Invalid Uri: $this->uri");
		$names = explode('/', $matches[1]);
		$this->setName(array_shift($names));
		$this->setNext(count($names) === 0 ? null : new PathFromArray($names));
		$this->isParsed = true;
	}
}