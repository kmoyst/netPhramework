<?php

namespace netPhramework\routing\redirectors;

use netPhramework\common\Variables;
use netPhramework\exchange\ResponseCode;
use netPhramework\routing\MutablePath;
use netPhramework\routing\rerouters\RerouteToSibling;

class RedirectToSibling extends Redirector
{
	public function __construct(
		MutablePath|string $subPath = '',
		?Variables         $parameters = null,
		ResponseCode       $code = ResponseCode::SEE_OTHER)
	{
		parent::__construct(new RerouteToSibling($subPath),$parameters,$code);
	}

}