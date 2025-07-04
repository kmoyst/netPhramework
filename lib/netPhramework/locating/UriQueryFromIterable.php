<?php

namespace netPhramework\locating;
use Stringable;
readonly class UriQueryFromIterable implements Stringable
{
	public function __construct(private iterable $iterable) {}

	public function get():string
	{
		return http_build_query(iterator_to_array($this->iterable));
	}

	public function __toString(): string
	{
		return $this->get();
	}
}