<?php

namespace netPhramework\core;

use netPhramework\exchange\Interpreter;
use netPhramework\exchange\Responder;
use netPhramework\exchange\Services;

interface Site
{
	public Application $application {get;}
	public Environment $environment {get;}
	public Interpreter $interpreter {get;}
	public Responder   $responder {get;}
	public Services    $services {get;}

	public function configureResponder(Responder $responder): void;
}