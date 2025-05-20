<?php

namespace netPhramework\responding;

use netPhramework\exceptions\NoContent;

interface Response
{
	/**
	 * @param Responder $responder
	 * @return void
	 * @throws NoContent
	 */
	public function deliver(Responder $responder):void;
}