<?php

namespace netPhramework\exchange;

use netPhramework\core\Node;
use netPhramework\core\Application;

readonly class ActiveSocket implements RequestSocket
{
	public function createNode(Request $request, Application $application):Node
	{
		$node = new Node();
		$request->location->getParameters()
			->clear()
			->merge($request->environment->postParameters);
		$application->buildActiveNode($node->root);
		return $node;
	}
}