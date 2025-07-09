<?php

namespace netPhramework\core;

use netPhramework\bootstrap\Application;
use netPhramework\rendering\Wrapper;

readonly class Site
{
	public function __construct
	(
	private Directory $passiveRoot 	= new Directory(''),
	private Directory $activeRoot 	= new Directory(''),
	public  Wrapper $wrapper 		= new Wrapper()
	)
	{}

	/**
	 * @param Application $configuration
	 * @return $this
	 * @throws \Exception
	 */
	public function configure(Application $configuration):Site
    {
		$configuration->buildPassiveTree($this->passiveRoot);
		$configuration->buildActiveTree($this->activeRoot);
		$configuration->configureWrapper($this->wrapper);
		return $this;
	}

	public function openPassiveSocket(): Socket
	{
		return new Socket($this->passiveRoot, $this->wrapper);
	}

	public function openActiveSocket(): Socket
	{
		return new Socket($this->activeRoot, $this->wrapper);
	}
}