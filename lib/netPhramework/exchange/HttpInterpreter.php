<?php

namespace netPhramework\exchange;

use netPhramework\core\Environment;
use netPhramework\routing\LocationFromUri;

class HttpInterpreter implements Interpreter
{
	public function interpret(Environment $environment):Request
	{
		if($environment->postParameters === null)
			$request = new ActiveRequest();
		else
			$request = new PassiveRequest();
		return $request
			->setLocation(new LocationFromUri($environment->uri));
	}
}