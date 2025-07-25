<?php

namespace netPhramework\routing;
use Stringable;

readonly class UriLocation implements Stringable
{
    public function __construct(private ReadableLocation $location) {}

	public function get():string
	{
		$glue  = '?';
		$path  = new UriPath($this->location->getPath());
		$query = new UriQuery($this->location->getParameters());
		return rtrim($path . $glue . $query, $glue);
	}

	public function __toString(): string
	{
		return $this->get();
	}
}