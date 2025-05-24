<?php

namespace netPhramework\responding;

use netPhramework\rendering\Encodable;

interface Relayer
{
	public function relay(Encodable $content, ResponseCode $code):void;
}