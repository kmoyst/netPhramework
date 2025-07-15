<?php

namespace netPhramework\cli;

use netPhramework\core\Configurator;
use netPhramework\core\Context;
use netPhramework\core\Environment;
use netPhramework\exchange\Request;
use netPhramework\exchange\Responder;
use netPhramework\exchange\Services;
use netPhramework\http\HttpServices;

class CliContext implements Context
{
	protected(set) Environment $env {get{
		if(!isset($this->env)){
			$this->env = new CliEnvironment();
			$dotenv = fopen('dotenv', 'r');
			while($line = fgets($dotenv))
			{
				preg_match('|^([A-Z_]+)=(.+)$|', $line, $m);
				$this->env->add($m[1], $m[2]);
			}
			fclose($dotenv);
		}
		return $this->env;
	}set{}}

	protected(set) Request $request {get{
		if(!isset($this->request))
			$this->request = new CliRequest();
		return $this->request;
	}set{}}

	protected(set) Responder $responder{get{
		if(!isset($this->responder))
			$this->responder = new CliResponder();
		return $this->responder;
	}set{}}

	protected(set) Services $services{get{
		if(!isset($this->services))
			$this->services = new HttpServices();
		return $this->services;
	}set{}}

	protected(set) Configurator $config{get{
		if(!isset($this->config))
			$this->config = new CliConfigurator();
		return $this->config;
	}set{}}
}