<?php

namespace netPhramework\core;
use netPhramework\exchange\Request;
use netPhramework\exchange\Responder;
use netPhramework\exchange\Services;
interface Context
{
	public Request $request {get;}
	public Responder $responder {get;}
	public Services $services {get;}
	public Environment $environment {get;}

	public function configureResponder(Responder $responder):void;
}