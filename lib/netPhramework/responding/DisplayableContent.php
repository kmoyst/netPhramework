<?php

namespace netPhramework\responding;

use netPhramework\rendering\Viewable;

readonly class DisplayableContent implements ResponseContent
{
	public function __construct(private Viewable $content) {}

	public function relay(Responder $responder, ResponseCode $code):void
	{
		$responder->display($this->content, $code);
	}
}