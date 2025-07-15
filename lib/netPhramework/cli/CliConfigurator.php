<?php

namespace netPhramework\cli;

use netPhramework\core\Configurator;
use netPhramework\exchange\Responder;

class CliConfigurator implements Configurator
{
	public function configureResponder(Responder $responder): void
	{
		$responder->templateFinder
			->directory('../templates/plain')
			->directory(__DIR__ . '/../../../templates/plain')
			->directory('../templates')
			->directory('../html')
			->directory(__DIR__ . '/../../../templates')
			->extension('tpl')
			->extension('phtml')
			->extension('css')
		;
	}
}