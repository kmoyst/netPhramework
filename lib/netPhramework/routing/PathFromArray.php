<?php

namespace netPhramework\routing;

class PathFromArray extends MutablePath
{
	public function __construct(private readonly array $names)
	{
		parent::__construct();
	}

	public function getName(): ?string
	{
		return $this->names[0] ?? null;
	}

	public function getNext(): ?MutablePath
	{
		if(count($this->names) > 1)
			return new PathFromArray(array_slice($this->names, 1));
		else
			return null;
	}
}