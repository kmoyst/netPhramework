<?php

namespace netPhramework\core;

use netPhramework\exchange\Responder;

interface Configurator
{
	public function configureResponder(Responder $responder):void;
}