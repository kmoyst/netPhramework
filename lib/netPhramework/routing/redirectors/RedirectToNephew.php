<?php

namespace netPhramework\routing\redirectors;

use netPhramework\common\Variables;
use netPhramework\exchange\ResponseCode;
use netPhramework\routing\Path;
use netPhramework\routing\rerouters\RerouteToNephew;

class RedirectToNephew extends Redirector
{
	public function __construct(
		string       $siblingName,
		string|Path  $subPath = '',
		?Variables   $parameters = null,
		ResponseCode $code = ResponseCode::SEE_OTHER)
	{
		parent::__construct(
			new RerouteToNephew($siblingName, $subPath), $parameters, $code);
	}

}