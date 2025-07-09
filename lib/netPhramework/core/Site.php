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

	public function configure(Configuration $configuration):Site
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