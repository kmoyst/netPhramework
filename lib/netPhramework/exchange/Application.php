<?php

namespace netPhramework\exchange;

use netPhramework\core\Configuration;
use netPhramework\exceptions\Exception;
use netPhramework\exceptions\ResourceNotFound;
use netPhramework\resources\Directory;
use netPhramework\routing\Navigator;

readonly class Application
{
	private Directory $root;

	public function __construct(private Configuration $configuration)
	{
		$this->root = new Directory('');
	}

	/**
	 * @return self
	 * @throws Exception
	 */
    public function asAPassiveResource():self
	{
		$this->configuration->configurePassiveNode($this->root);
		return $this;
	}

	/**
	 * @return self
	 * @throws Exception
	 */
	public function asAnActiveResource():self
	{
		$this->configuration->configureActiveNode($this->root);
		return $this;
	}

	/**
	 * @param Exchange $exchange
	 * @return void
	 * @throws ResourceNotFound
	 */
	public function handleExchange(Exchange $exchange):void
	{
		new Navigator()
			->setRoot($this->root)
			->setPath($exchange->path)
			->navigate()
			->handleExchange($exchange)
		;
	}
}