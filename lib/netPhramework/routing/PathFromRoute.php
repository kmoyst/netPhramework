<?php

namespace netPhramework\routing;

use netPhramework\exceptions\PathException;

class PathFromRoute extends PathTemplate
{
	public function __construct(private readonly Route $route) {}

	/**
	 * @return void
	 * @throws PathException
	 */
	protected function parse():void
	{
		$this->setName($this->route->getName());
		if(($nextRoute = $this->route->getNext()) !== null) {
			$this->appendRoute($nextRoute);
		}
	}
}