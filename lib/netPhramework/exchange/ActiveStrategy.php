<?php

namespace netPhramework\exchange;

class ActiveStrategy extends ResourceStrategy
{
	public function configure(ExchangeHandler $handler):void
	{
		$this->location->getParameters()
			->clear()
			->merge($this->environment->postParameters);
		$this->application->asAnActiveResource($handler->root);
	}
}