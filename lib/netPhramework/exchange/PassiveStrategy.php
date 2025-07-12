<?php

namespace netPhramework\exchange;

class PassiveStrategy extends ResourceStrategy
{
	public function configure(ExchangeHandler $handler):void
	{
		$this->application->asAPassiveResource($handler->root);
	}
}