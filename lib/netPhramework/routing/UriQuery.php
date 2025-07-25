<?php

namespace netPhramework\routing;
use Stringable;
readonly class UriQuery implements Stringable
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