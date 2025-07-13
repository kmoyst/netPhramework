<?php

namespace netPhramework\exchange;

use netPhramework\core\Site;
use netPhramework\exceptions\Exception;
use netPhramework\routing\Location;

abstract class RequestStrategy
{
	protected Site $site;
	protected Location $location;
	protected Application $application;

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
		$configuration = $this->site->getConfiguration();
		$this->application = new Application($configuration);
		return $this;
	}

	/**
	 * @return Application
	 * @throws Exception
	 */
	abstract public function requestApplication():Application;
}