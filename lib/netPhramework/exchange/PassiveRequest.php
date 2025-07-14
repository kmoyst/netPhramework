<?php

namespace netPhramework\exchange;

use netPhramework\core\Application;

class PassiveRequest extends Request
{
	public function dispatch(Application $application): void
	{
		$application->configurePassiveNode($this->root);
	}
}