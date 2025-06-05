<?php

namespace netPhramework\locating\redirectors;

use netPhramework\common\Variables;
use netPhramework\locating\MutablePath;
use netPhramework\locating\rerouters\RerouteToNephew;
use netPhramework\responding\ResponseCode;

class RedirectToNephew extends Redirector
{
	public function __construct(
		string $siblingName,
		string|MutablePath $subPath = '',
		?Variables $parameters = null,
		ResponseCode $code = ResponseCode::SEE_OTHER)
	{
		parent::__construct(
			new RerouteToNephew($siblingName, $subPath), $parameters, $code);
	}

}