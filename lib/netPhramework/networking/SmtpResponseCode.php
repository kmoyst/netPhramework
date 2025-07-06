<?php

namespace netPhramework\networking;

enum SmtpResponseCode:int
{
	case SERVICE_READY = 220;
	case GOODBYE = 221;
	case AUTH_SUCCESS = 235;
	case OK = 250;
	case WILL_FORWARD = 251;
	case CANNOT_VERIFY = 252;
	case START_MAIL_INPUT = 354;

	public function test(?int $code):bool
	{
		return $code === $this->value;
	}
}