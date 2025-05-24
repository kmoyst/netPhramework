<?php

namespace netPhramework\responding;

interface Response
{
	public function deliver(Responder $responder):void;
}