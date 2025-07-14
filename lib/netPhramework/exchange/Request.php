<?php

namespace netPhramework\exchange;

use netPhramework\core\Application;
use netPhramework\core\Environment;
use netPhramework\exceptions\Exception;
use netPhramework\nodes\Node;
use netPhramework\routing\Location;

abstract class Request
{
	protected(set) Location $location;
	protected Environment $environment;
	protected Route $route;

	public function setLocation(Location $location): self
	{
		$this->location = $location;
		return $this;
	}

	public function setEnvironment(Environment $environment): self
	{
		$this->environment = $environment;
		return $this;
	}

	public function routeThrough(Application $application):self
	{
		$this->route = new Route($application);
		return $this;
	}
	/**
	 * @return Node
	 * @throws Exception
	 */
	abstract public function andGetNode():Node;
}