<?php

namespace netPhramework\http;

use netPhramework\core\Context;
use netPhramework\core\Environment;
use netPhramework\exchange\Request;
use netPhramework\exchange\Responder;
use netPhramework\exchange\Services;

class HttpContext implements Context
{
	protected(set) Request $request {get{
		return new HttpRequest();
	}set{}}

	protected(set) Responder $responder{get{
		return new HttpResponder();
	}set{}}

	protected(set) Services $services{get{
		return new HttpServices();
	}set{}}

	protected(set) Environment $environment {get{
		return new HttpEnvironment();
	}set{}}

	public function configureResponder(Responder $responder): void
	{
		$responder->wrapper->addStyleSheet('framework-stylesheet');
		$responder->templateFinder
			->directory('../templates')
			->directory('../html')
			->directory(__DIR__ . '/../../../templates')
			->extension('tpl')
			->extension('phtml')
			->extension('css');
	}
}