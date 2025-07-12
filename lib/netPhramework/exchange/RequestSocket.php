<?php

namespace netPhramework\exchange;

use netPhramework\core\Application;
use netPhramework\core\Node;
use netPhramework\exceptions\Exception;

interface RequestSocket
{
	/**
	 * @param Request $request
	 * @param Application $application
	 * @return Node
	 * @throws Exception
	 */
	public function createNode(Request $request, Application $application):Node;
}