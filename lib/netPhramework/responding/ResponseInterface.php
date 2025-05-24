<?php

namespace netPhramework\responding;

interface ResponseInterface
{
	public function deliver(Responder $responder):void;
}