<?php

namespace netPhramework\http;

use netPhramework\core\Configurator;
use netPhramework\exchange\Responder;

class HttpConfigurator implements Configurator
{
	public function configureResponder(Responder $responder): void
	{
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