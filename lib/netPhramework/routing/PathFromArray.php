<?php

namespace netPhramework\routing;

class PathFromArray extends Path
{
	public function __construct(private readonly array $names) {}

	public function getName(): ?string
	{
		if(parent::getName() === null)
			$this->setName($this->names[0]);
		return parent::getName();
	}

	public function getNext(): ?Path
	{
		if(parent::getNext() === null && count($this->names) > 1)
			$this->setNext(new PathFromArray(array_slice($this->names, 1)));
		return parent::getNext();
	}
}