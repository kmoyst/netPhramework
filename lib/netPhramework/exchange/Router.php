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
	 * @return Dispatcher
	 * @throws NodeNotFound
	 */
	public function route(Location $location):Dispatcher
	{
		$handler = new Navigator()
			->setRoot($this->root)
			->setPath($location->path)
			->navigate();
		return new Dispatcher($handler, $location);
	}
}