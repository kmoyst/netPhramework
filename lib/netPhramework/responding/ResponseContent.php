<?php

namespace netPhramework\responding;

interface ResponseContent
{
	public function relay(Responder $responder, ResponseCode $code):void;
}