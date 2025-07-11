<?php

namespace netPhramework\core;

use netPhramework\exceptions\Exception;

class Site
{
	public NodeBuilder $nodeBuilder;

	/**
	 * @return Node
	 * @throws Exception
	 */
	public function openPassiveNode(): Node
	{
		$socket = new Node();
		$this->nodeBuilder->buildPassiveNode($socket->root);
		return $socket;
	}

	/**
	 * @return Node
	 * @throws Exception
	 */
	public function openActiveNode(): Node
	{
		$socket = new Node();
		$this->nodeBuilder->buildActiveNode($socket->root);
		return $socket;
	}
}