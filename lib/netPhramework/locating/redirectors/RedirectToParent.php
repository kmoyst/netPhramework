<?php

namespace netPhramework\locating\redirectors;

use netPhramework\common\Variables;
use netPhramework\exchange\ResponseCode;
use netPhramework\locating\MutablePath;
use netPhramework\locating\rerouters\RerouteToParent;

class RedirectToParent extends Redirector
{
	public function __construct(
		MutablePath|string $subPath = '',
		?Variables         $parameters = null,
		ResponseCode       $code = ResponseCode::SEE_OTHER)
	{
		parent::__construct(new RerouteToParent($subPath), $parameters, $code);
	}

}