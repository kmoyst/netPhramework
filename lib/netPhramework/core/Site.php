<?php

namespace netPhramework\core;

use netPhramework\bootstrap\Configuration;
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
	 * @param Configuration $configuration
	 * @return $this
	 * @throws Exception
	 */
	public function configure(Configuration $configuration):Site
    {
		try
		{
			$configuration->buildPassiveTree($this->passiveRoot);
			$configuration->buildActiveTree($this->activeRoot);
			$configuration->configureWrapper($this->wrapper);
			return $this;
		}
		catch (Exception $exception)
		{
			throw $exception->setWrapper($this->wrapper);
		}
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