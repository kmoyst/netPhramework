<?php

namespace netPhramework\dispatching\redirectors;

use netPhramework\common\Variables;
use netPhramework\dispatching\MutablePath;
use netPhramework\dispatching\rerouters\RerouteToParent;
use netPhramework\responding\ResponseCode;

readonly class RedirectToParent extends Redirector
{
	public function __construct(MutablePath|string $subPath = '',
								?Variables         $parameters = null,
								ResponseCode       $code = ResponseCode::SEE_OTHER)
	{
		parent::__construct(new RerouteToParent($subPath), $parameters, $code);
	}

}