<?php

namespace netPhramework\responding;

use netPhramework\dispatching\Location;
use netPhramework\rendering\Viewable;

readonly class Displayable implements ResponseContent
{
	public function __construct(private Viewable $content) {}

	public function relay(Responder $responder, ResponseCode $code):void
	{
		$responder->display($this->content, $code);
	}
}