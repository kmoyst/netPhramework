<?php

namespace netPhramework\exchange;

use netPhramework\bootstrap\Environment;
use netPhramework\site\Application;
use netPhramework\exceptions\Exception;
use netPhramework\routing\Location;

abstract class RequestStrategy
{
	protected Application $application;
	protected Location $location;
	protected Environment $environment;

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

	public function setApplication(Application $application): self
	{
		$this->application = $application;
		return $this;
	}

	/**
	 * @param ExchangeHandler $handler
	 * @return void
	 * @throws Exception
	 */
	abstract public function configure(ExchangeHandler $handler):void;
}