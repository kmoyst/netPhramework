<?php

namespace netPhramework\routing;

class PathFromArray extends Path
{
	public ?Path $next = null {get{
		if(count($this->names) > 1)
			return new PathFromArray(array_slice($this->names, 1));
		else
			return null;
	}}

	public ?string $name = null {get{
		return $this->names[0] ?? null;
	}}

	public function __construct(private readonly array $names)
	{
	}
}