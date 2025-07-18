<?php

namespace netPhramework\cli;

use netPhramework\core\Context;
use netPhramework\core\Environment;
use netPhramework\exchange\Request;
use netPhramework\exchange\Responder;
use netPhramework\exchange\Services;

class CliContext implements Context
{
	public Environment $environment {get{
		$environment = new CliEnvironment();
		$dotenv = fopen('dotenv', 'r');
		while($line = fgets($dotenv))
		{
			preg_match('|^([A-Z_]+)=(.+)$|', $line, $m);
			$environment->add($m[1], $m[2]);
		}
		fclose($dotenv);
		return $environment;
	}}

	private(set) Request $request {get{
		if(!isset($this->request))
			$this->request = new CliRequest($this->environment);
		return $this->request;
	}}

	public Responder $responder{get{
		return new CliResponder();
	}}

	public Services $services{get{
		return new CliServices();
	}}

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