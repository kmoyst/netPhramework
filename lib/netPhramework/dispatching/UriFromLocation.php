<?php

namespace netPhramework\dispatching;
use Stringable;

/**
 * Converts and Location to a URI string
 */
readonly class UriFromLocation implements Stringable
{
    public function __construct(private Location $location) {}

	public function get():string
	{
		$s = '/';
		$q = '?';
		$names = [];
        $path = $this->location->getPath();
        $params = iterator_to_array($this->location->getParameters());
		$this->traversePath($names, $path);
		$uriPath = $s.implode($s, $names);
		$uriQuery = http_build_query($params);
		return $uriPath . ( !empty($uriQuery) ? $q . $uriQuery : '' );
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