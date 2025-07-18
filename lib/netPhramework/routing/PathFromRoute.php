<?php

namespace netPhramework\routing;

class PathFromRoute extends Path
{
	public function __construct(private readonly Route $route) {}

	public function getName(): ?string
	{
		if(parent::getName() === null)
			$this->setName($this->route->getName());
		return parent::getName();
	}

	public function getNext(): ?Path
	{
		if(parent::getNext() === null)
		{
			$nextRoute = $this->route->getNext();
			if($nextRoute !== null) {
				$nextPath  = new Path()->setName($nextRoute->getName());
				$nextPath->setNext($nextRoute->getNext());
				$this->setNext($nextPath);
			}
		}
		return parent::getNext();
	}
}