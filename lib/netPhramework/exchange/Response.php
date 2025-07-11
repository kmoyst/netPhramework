<?php

namespace netPhramework\exchange;

interface Response
{
	public function deliver(Responder $responder):void;
}