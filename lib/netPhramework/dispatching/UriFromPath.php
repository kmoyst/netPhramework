<?php

namespace netPhramework\dispatching;

use netPhramework\dispatching\interfaces\ReadablePath;
use Stringable;

/**
 * Converts a ReadablePath to uri string
 */
readonly class UriFromPath implements Stringable
{
	public function __construct(private ReadablePath $path) {}

	public function get():string
	{
		$s = '/';
		$names = [];
		$this->traversePath($names, $this->path);
		return $s.implode($s, $names);
	}

	private function traversePath(array &$names, ?ReadablePath $path):void
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