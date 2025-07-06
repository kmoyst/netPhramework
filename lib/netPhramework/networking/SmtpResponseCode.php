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

	/**
	 * @param int|null $code
	 * @return bool
	 * @throws EmailException
	 */
	public function confirm(?int $code):bool
	{
		if(!$this->test($code))
			throw new SmtpResponseFailed(
				"Smtp Response Failed with code: $code");
		return true;
	}

	public function test(?int $code):bool
	{
		return $code === $this->value;
	}
}