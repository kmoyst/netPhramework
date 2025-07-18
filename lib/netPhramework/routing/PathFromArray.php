<?php

namespace netPhramework\routing;

class PathFromArray extends PathTemplate
{
	public function __construct(private readonly array $names) {}

	protected function parse():void
	{
		$this->setName($this->names[0]);
		if(count($this->names) > 1)
			$this->setNext(new PathFromArray(array_slice($this->names, 1)));
	}
}