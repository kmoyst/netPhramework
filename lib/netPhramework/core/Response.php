<?php

namespace netPhramework\core;

interface Response
{
	public function deliver(Responder $responder):void;
}