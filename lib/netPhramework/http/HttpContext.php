<?php

namespace netPhramework\http;

use netPhramework\core\Context;
use netPhramework\core\Environment;
use netPhramework\exchange\Request;
use netPhramework\exchange\Responder;
use netPhramework\exchange\Services;

class HttpContext implements Context
{
	public Request $request {get{
		return new HttpRequest();
	}}

	public Responder $responder{get{
		return new HttpResponder();
	}}

	public Services $services{get{
		return new HttpServices();
	}}

	public Environment $environment {get{
		return new HttpEnvironment();
	}}

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