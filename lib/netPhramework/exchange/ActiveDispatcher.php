<?php

namespace netPhramework\exchange;

class ActiveDispatcher extends Dispatcher
{
	public function dispatch():Router
	{
		$this->location->getParameters()
			->clear()
			->merge($this->site->environment->postParameters);
		return $this->router->withAnActiveNode();
	}
}