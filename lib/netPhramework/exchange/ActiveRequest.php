<?php

namespace netPhramework\exchange;

use netPhramework\core\Application;

class ActiveRequest extends Request
{
	public function dispatch(Application $application): void
	{
		$this->location->getParameters()
			->clear()
			->merge($this->environment->postParameters);
		$application->configureActiveNode($this->root);
	}
}