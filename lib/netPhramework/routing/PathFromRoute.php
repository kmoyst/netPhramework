<?php

namespace netPhramework\routing;

class PathFromRoute extends Path
{
	public function __construct(private readonly Route $route)
	{

	}

	public function getName(): ?string
	{
		return $this->route->getName() ?? parent::getName();
	}

	public function getNext(): ?Path
	{
		if(parent::getNext() !== null)
			return parent::getNext();
		$nextRoute = $this->route->getNext();
		if($nextRoute !== null)
		{
			$nextPath = new Path()->setName($nextRoute->getName());
			$nextPath->setNext($nextRoute->getNext());
			parent::setNext($nextPath);
			return $nextPath;
		}
		else return null;
	}

	public function appendPath(Path $tail): Path
	{
		if($this->getNext() === null)
			parent::appendPath($tail);
		else
			$this->getNext()->appendPath($tail);
		return $this;
	}
}