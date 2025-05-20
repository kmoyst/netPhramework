<?php

namespace netPhramework\core;


use netPhramework\bootstrap\Configuration;
use netPhramework\exceptions\Exception;
use netPhramework\rendering\Wrapper;

class Application
{
	private Directory $passiveNode;
	private Directory $activeNode;
	private Wrapper $wrapper;

	public function __construct()
	{
		$this->passiveNode = new Directory('');
		$this->activeNode  = new Directory('');
		$this->wrapper	   = new Wrapper();
	}

	/**
	 * @param Configuration $configuration
	 * @return $this
	 * @throws \Exception
	 */
	public function configure(Configuration $configuration):Application
    {
		try
		{
			$configuration->configureWrapper($this->wrapper);
			$configuration->configurePassiveNode($this->passiveNode);
			$configuration->configureActiveNode($this->activeNode);
			return $this;
		}
		catch (Exception $exception)
		{
			throw $exception->setWrapper($this->wrapper);
		}
	}

	public function openPassiveSocket(): Socket
	{
		return $this->openSocket($this->passiveNode);
	}

	public function openActiveSocket(): Socket
	{
		return $this->openSocket($this->activeNode);
	}

	private function openSocket(Directory $root): Socket
	{
		return new Socket($root, $this->wrapper);
	}
}