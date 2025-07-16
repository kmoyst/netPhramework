<?php

namespace netPhramework\routing\redirectors;

use netPhramework\common\Variables;
use netPhramework\exchange\ResponseCode;
use netPhramework\routing\Path;
use netPhramework\routing\rerouters\RerouteToChild;

class RedirectToChild extends Redirector
{
	public function __construct(
		Path|string  $subPath = '',
		?Variables   $parameters = null,
		ResponseCode $code = ResponseCode::SEE_OTHER)
	{
		parent::__construct(new RerouteToChild($subPath), $parameters, $code);
	}

}