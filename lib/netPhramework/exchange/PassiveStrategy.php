<?php

namespace netPhramework\exchange;

class PassiveStrategy extends RequestStrategy
{
	public function configure(Site $handler):void
	{
		$this->application->asAPassiveResource($handler->root);
	}
}