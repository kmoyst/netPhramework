<?php

namespace netPhramework\exchange;

use netPhramework\core\Application;

class ActiveStrategy extends RequestStrategy
{
	public function requestApplication():Application
	{
		$this->location->getParameters()
			->clear()
			->merge($this->site->environment->postParameters);
		return $this->application->asAnActiveResource();
	}
}