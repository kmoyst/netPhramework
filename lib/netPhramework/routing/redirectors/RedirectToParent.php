<?php

namespace netPhramework\routing\redirectors;

use netPhramework\common\Variables;
use netPhramework\exchange\ResponseCode;
use netPhramework\routing\Path;
use netPhramework\routing\rerouters\RerouteToParent;

class RedirectToParent extends Redirector
{
	public function __construct(
		Path|string  $subPath = '',
		?Variables   $parameters = null,
		ResponseCode $code = ResponseCode::SEE_OTHER)
	{
		parent::__construct(new RerouteToParent($subPath), $parameters, $code);
	}

}