<?php

namespace netPhramework\responding;

use netPhramework\rendering\Encodable;

interface ResponseContent extends Encodable
{
	public function chooseRelay(Responder $responder):Relayer;
}