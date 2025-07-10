<?php

namespace netPhramework\core;

use netPhramework\bootstrap\Application;
use netPhramework\rendering\Wrapper;

class Site
{
	private Application $application;

	public function __construct
	(
	public readonly Wrapper $wrapper = new Wrapper()
	)
	{}

	public function setApplication(Application $application): self
	{
		$this->application = $application;
		return $this;
	}

	public function configure():self
	{
		$this->application->configureWrapper($this->wrapper);
		return $this;
	}

	/**
	 * @return Socket
	 * @throws Exception
	 */
	public function openPassiveSocket(): Socket
	{
		$root = new Directory('');
		$this->application->buildPassiveTree($root);
		return new Socket($root, $this->wrapper);
	}

	/**
	 * @return Socket
	 * @throws Exception
	 */
	public function openActiveSocket(): Socket
	{
		$root = new Directory('');
		$this->application->buildActiveTree($root);
		return new Socket($root, $this->wrapper);
	}
}