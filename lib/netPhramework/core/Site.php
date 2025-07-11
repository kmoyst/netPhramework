<?php

namespace netPhramework\core;

use netPhramework\exceptions\Exception;

class Site
{
	public NodeBuilder $configurator;

	/**
	 * @return Node
	 * @throws Exception
	 */
	public function openPassiveNode(): Node
	{
		$socket = new Node();
		$this->configurator->buildPassiveNode($socket->root);
		return $socket;
	}

	/**
	 * @return Node
	 * @throws Exception
	 */
	public function openActiveNode(): Node
	{
		$socket = new Node();
		$this->configurator->buildActiveNode($socket->root);
		return $socket;
	}
}