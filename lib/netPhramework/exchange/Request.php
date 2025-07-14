<?php

namespace netPhramework\exchange;

use netPhramework\core\Application;
use netPhramework\core\Environment;
use netPhramework\exceptions\Exception;
use netPhramework\nodes\Directory;
use netPhramework\routing\Location;

abstract class Request
{
	protected Environment $environment;
	protected(set) Location $location;
	protected(set) Directory $root;

	public function __construct()
	{
		$this->root = new Directory('');
	}

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

	/**
	 * @param Application $application
	 * @return void
	 * @throws Exception
	 */
	abstract public function dispatch(Application $application):void;
}