<?php

namespace netPhramework\exchange;

class PassiveStrategy extends RequestStrategy
{
	public function configure(ExchangeHandler $handler):void
	{
		$this->application->asPassive($handler->root);
	}
}