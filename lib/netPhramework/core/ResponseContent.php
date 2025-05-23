<?php

namespace netPhramework\core;

interface ResponseContent
{
	public function relay(Responder $responder, ResponseCode $code):void;
}