<?php

namespace netPhramework\exchange;

class ActiveStrategy extends RequestStrategy
{
	public function configure(Site $handler):void
	{
		$this->location->getParameters()
			->clear()
			->merge($this->environment->postParameters);
		$this->application->asAnActiveResource($handler->root);
	}
}