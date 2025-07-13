<?php

namespace netPhramework\exchange;

use netPhramework\core\Application;

class PassiveStrategy extends RequestStrategy
{
	public function requestApplication(): Application
	{
		return $this->application->asAPassiveResource();
	}
}