<?php

namespace netPhramework\http;

use netPhramework\core\Site;
use netPhramework\exchange\Responder;

abstract class HttpSite extends Site
{
	public function __construct()
	{
		parent::__construct(
			new HttpEnvironment(), new HttpInterpreter(),
			new HttpResponder(), new HttpServices());
	}

	public function configureResponder(Responder $responder): void
	{
		$responder->wrapper->addStyleSheet('framework-stylesheet');
		$responder->templateFinder
			->directory('../templates')
			->directory('../html')
			->directory(__DIR__ . '/../../../templates')
			->extension('tpl')
			->extension('phtml')
			->extension('css')
		;
	}
}