<?php

namespace netPhramework\site;

use netPhramework\exceptions\Exception;

class Site
{
	public Application $application;

	/**
	 * @return Node
	 * @throws Exception
	 */
	public function openPassiveNode(): Node
	{
		$node = new Node();
		$this->application->buildPassiveNode($node->root);
		return $node;
	}

	/**
	 * @return Node
	 * @throws Exception
	 */
	public function openActiveNode(): Node
	{
		$node = new Node();
		$this->application->buildActiveNode($node->root);
		return $node;
	}
}