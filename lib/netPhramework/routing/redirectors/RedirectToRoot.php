<?php

namespace netPhramework\routing\redirectors;

use netPhramework\common\Variables;
use netPhramework\exchange\ResponseCode;
use netPhramework\routing\Path;
use netPhramework\routing\rerouters\RerouteToRoot;

class RedirectToRoot extends Redirector
{
	public function __construct(
		Path|string  $subPath = '',
		?Variables   $parameters = null,
		ResponseCode $code = ResponseCode::SEE_OTHER)
	{
		parent::__construct(new RerouteToRoot($subPath), $parameters, $code);
	}

}