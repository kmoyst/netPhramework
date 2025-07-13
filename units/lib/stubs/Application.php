<?php

namespace stubs;

use netPhramework\nodes\Directory;

class Application implements \netPhramework\core\Application
{
	public function configurePassiveNode(Directory $root): void
	{
		$root->permitAutoIndex();
	}

	public function configureActiveNode(Directory $root): void
	{

	}
}