<?php

namespace netPhramework\locating\redirectors;

use netPhramework\common\Variables;
use netPhramework\locating\MutablePath;
use netPhramework\locating\rerouters\RerouteToSibling;
use netPhramework\responding\ResponseCode;

readonly class RedirectToSibling extends Redirector
{
	public function __construct(MutablePath|string $subPath = '',
								?Variables         $parameters = null,
								ResponseCode       $code = ResponseCode::SEE_OTHER)
	{
		parent::__construct(new RerouteToSibling($subPath),$parameters,$code);
	}

}