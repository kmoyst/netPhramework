<?php

namespace netPhramework\routing;

use Stringable;

readonly class UriPath implements Stringable
{
	public function __construct(private Route $path) {}

	public function get():string
	{
		$names = [];
		$this->traversePath($names, $this->path);
		return '/'.implode('/', $names);
	}

	private function traversePath(array &$names, ?Route $path):void
	{
		if($path === null) return;
		$names[] = $path->getName();
		$this->traversePath($names, $path->getNext());
	}

	public function __toString(): string
	{
		return $this->get();
	}
}