<?php

namespace netPhramework\exchange;

use netPhramework\core\Site;
use netPhramework\exceptions\Exception;
use netPhramework\routing\Location;

abstract class Dispatcher
{
	protected Site $site;
	protected Location $location;
	protected Router $router;

	public function setSite(Site $site): self
	{
		$this->site = $site;
		return $this;
	}

	public function setLocation(Location $location): self
	{
		$this->location = $location;
		return $this;
	}

	public function prepare():self
	{
		$application = $this->site->getApplication();
		$this->router = new Router($application);
		return $this;
	}

	/**
	 * @return Router
	 * @throws Exception
	 */
	abstract public function dispatch():Router;
}