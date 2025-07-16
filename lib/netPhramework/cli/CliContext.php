<?php

namespace netPhramework\cli;

use netPhramework\core\Context;
use netPhramework\core\Environment;
use netPhramework\exchange\Request;
use netPhramework\exchange\Responder;
use netPhramework\exchange\Services;
use netPhramework\http\HttpServices;

class CliContext implements Context
{
	protected(set) Environment $environment {get{
		$environment = new CliEnvironment();
		$dotenv = fopen('dotenv', 'r');
		while($line = fgets($dotenv))
		{
			preg_match('|^([A-Z_]+)=(.+)$|', $line, $m);
			$environment->add($m[1], $m[2]);
		}
		fclose($dotenv);
		return $environment;
	} set{}}

	protected(set) Request $request {get{
		return new CliRequest($this->environment);
	} set{}}

	protected(set) Responder $responder{get{
		return new CliResponder();
	} set{}}

	protected(set) Services $services{get{
		return new HttpServices();
	} set{}}

	public function configureResponder(Responder $responder):void
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