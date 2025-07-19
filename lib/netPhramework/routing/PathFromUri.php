<?php

namespace netPhramework\routing;

use netPhramework\exceptions\InvalidUri;

class PathFromUri extends PathTemplate
{
	public function __construct(private readonly string $uri) {}

	/**
	 * @return void
	 * @throws InvalidUri
	 */
	protected function parse():void
	{
		if(!preg_match('|^/([^?]*)|', $this->uri, $matches))
			throw new InvalidUri("Invalid Uri: $this->uri");
		$names = explode('/', $matches[1]);
		$this->setName(array_shift($names));
		$this->setNext(count($names) === 0 ? null : new PathFromArray($names));
	}
}