<?php

namespace netPhramework\exchange;

use netPhramework\exceptions\NodeNotFound;
use netPhramework\nodes\Directory;
use netPhramework\routing\Location;

readonly class Router
{
	public function __construct(private Directory $root)
	{
	}

	/**
	 * @param Location $location
	 * @return Action
	 * @throws NodeNotFound
	 */
	public function route(Location $location):Action
	{
		$handler = new Navigator()
			->setRoot($this->root)
			->setRoute($location->path)
			->navigate();
		return new Action($handler, $location);
	}
}