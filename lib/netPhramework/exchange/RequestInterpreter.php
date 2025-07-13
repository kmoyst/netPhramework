<?php

namespace netPhramework\exchange;

use netPhramework\core\Environment;

class RequestInterpreter
{
	public function interpret(Environment $environment):Request
	{
		if($environment->postParameters === null)
			return new Request(new PassiveDispatcher());
		else
			return new Request(new ActiveDispatcher());
	}
}