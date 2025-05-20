<?php

namespace netPhramework\responding;

use netPhramework\dispatching\Location;

readonly class Redirectable implements ResponseContent
{
    public function __construct(private Location $location) {}

	public function relay(Responder $responder, ResponseCode $code): void
	{
		$responder->redirect($this->location, $code);
	}
}