<?php

namespace netPhramework\exchange;

class PassiveStrategy extends RequestStrategy
{
	public function requestApplication(): Application
	{
		return $this->application->asAPassiveResource();
	}
}