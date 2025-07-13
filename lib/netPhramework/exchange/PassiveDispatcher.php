<?php

namespace netPhramework\exchange;

class PassiveDispatcher extends Dispatcher
{
	public function dispatch(): Router
	{
		return $this->router->withAPassiveNode();
	}
}