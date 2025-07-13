<?php

namespace netPhramework\routing;

use Stringable;

readonly class UriFromPath implements Stringable
{
	public function __construct(private Path $path) {}

	public function get():string
	{
		$s = '/';
		$names = [];
		$this->traversePath($names, $this->path);
		return $s.implode($s, $names);
	}

	private function traversePath(array &$names, ?Path $path):void
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