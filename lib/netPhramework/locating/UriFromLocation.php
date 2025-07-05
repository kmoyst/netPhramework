<?php

namespace netPhramework\locating;
use Stringable;

readonly class UriFromLocation implements Stringable
{
    public function __construct(private ReadableLocation $location) {}

	public function get():string
	{
		$glue  = '?';
		$path  = new UriFromPath($this->location->getPath());
		$query = new UriQueryFromIterable($this->location->getParameters());
		return rtrim($path . $glue . $query, $glue);
	}

	public function __toString(): string
	{
		return $this->get();
	}
}