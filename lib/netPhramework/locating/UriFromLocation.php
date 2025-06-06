<?php

namespace netPhramework\locating;
use Stringable;

readonly class UriFromLocation implements Stringable
{
    public function __construct(private Location $location) {}

	public function get():string
	{
		$q			= '?';
		$path 		= new UriFromPath($this->location->getPath());
        $params 	= iterator_to_array($this->location->getParameters());
		$uriQuery 	= http_build_query($params);
		return rtrim("$path$q$uriQuery",$q);
	}

	public function __toString(): string
	{
		return $this->get();
	}
}