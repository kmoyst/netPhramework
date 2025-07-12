<?php

namespace netPhramework\exchange;

use netPhramework\core\Application;
use netPhramework\core\Node;

readonly class PassiveSocket implements RequestSocket
{
	public function createNode(Request $request, Application $application):Node
	{
		$node = new Node();
		$application->buildPassiveNode($node->root);
		return $node;
	}
}