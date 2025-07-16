<?php

namespace netPhramework\routing;

class PathFromArray extends Path
{
	public function __construct(private readonly array $names) {}

	public function getName(): ?string
	{
		return $this->names[0] ?? null;
	}

	public function getNext(): ?self
	{
		if(count($this->names) > 1)
			return new PathFromArray(array_slice($this->names, 1));
		else
			return null;
	}
}