<?php

namespace netPhramework\core;

use netPhramework\exceptions\Exception;

class Application
{
	public NodeBuilder $nodeBuilder;

	/**
	 * @return Node
	 * @throws Exception
	 */
	public function openPassiveNode(): Node
	{
		$node = new Node();
		$this->nodeBuilder->buildPassiveNode($node->root);
		return $node;
	}

	/**
	 * @return Node
	 * @throws Exception
	 */
	public function openActiveNode(): Node
	{
		$node = new Node();
		$this->nodeBuilder->buildActiveNode($node->root);
		return $node;
	}
}