<?php

namespace netPhramework\dispatching\redirectors;

use netPhramework\common\Variables;
use netPhramework\dispatching\MutablePath;
use netPhramework\dispatching\rerouters\RerouteToSibling;
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