<?php

namespace netPhramework\core;

use netPhramework\application\Configurator;
use netPhramework\exceptions\Exception;

class Site
{
	public Configurator $configurator;

	/**
	 * @return Node
	 * @throws Exception
	 */
	public function openPassiveNode(): Node
	{
		$socket = new Node();
		$this->configurator->configurePassiveNode($socket->root);
		return $socket;
	}

	/**
	 * @return Node
	 * @throws Exception
	 */
	public function openActiveNode(): Node
	{
		$socket = new Node();
		$this->configurator->configureActiveNode($socket->root);
		return $socket;
	}
}