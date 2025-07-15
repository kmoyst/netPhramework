<?php

namespace netPhramework\http;

use netPhramework\core\Environment;
use netPhramework\exchange\ActiveRequest;
use netPhramework\exchange\Interpreter;
use netPhramework\exchange\PassiveRequest;
use netPhramework\exchange\Request;
use netPhramework\routing\LocationFromUri;

class HttpInterpreter implements Interpreter
{
	/**
	 * @param Environment $environment
	 * @return Request
	 */
	public function interpret(Environment $environment):Request
	{
		if($environment->postParameters === null)
			$request = new PassiveRequest();
		else
			$request = new ActiveRequest();
		return $request
			->setLocation(new LocationFromUri($environment->uri));
	}
}