<?php

namespace netPhramework\http;

use netPhramework\core\Context;
use netPhramework\core\Environment;
use netPhramework\exchange\Request;
use netPhramework\exchange\Responder;
use netPhramework\exchange\Services;
use netPhramework\routing\CallbackManager;
use netPhramework\transferring\FileManager;
use netPhramework\transferring\SmtpServer;
use netPhramework\user\Session;

class HttpContext implements Context
{
	public Request $request {get{
		return new HttpRequest();
	}}

	public Responder $responder{get{
		return new HttpResponder();
	}}

	public Services $services{get{
		return new Services(
			new Session(),
			new FileManager(),
			new CallbackManager(),
			new SmtpServer($this->environment)
		);
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