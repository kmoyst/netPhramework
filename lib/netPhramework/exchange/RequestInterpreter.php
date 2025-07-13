<?php

namespace netPhramework\exchange;

use netPhramework\bootstrap\Environment;

class RequestInterpreter
{
	public function interpret(Environment $environment):Request
	{
		if($environment->postParameters === null)
			return new Request(new PassiveStrategy());
		else
			return new Request(new ActiveStrategy());
	}
}