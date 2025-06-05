<?php

namespace netPhramework\locating\redirectors;

use netPhramework\common\Variables;
use netPhramework\locating\MutablePath;
use netPhramework\locating\rerouters\RerouteToChild;
use netPhramework\responding\ResponseCode;

readonly class RedirectToChild extends Redirector
{
	public function __construct(
		MutablePath|string $subPath = '',
		?Variables   	   $parameters = null,
		ResponseCode 	   $code = ResponseCode::SEE_OTHER)
	{
		parent::__construct(new RerouteToChild($subPath), $parameters, $code);
	}

}