<?php

namespace netPhramework\dispatching\redirectors;

use netPhramework\common\Variables;
use netPhramework\dispatching\MutablePath;
use netPhramework\dispatching\rerouters\RerouteToChild;
use netPhramework\responding\ResponseCode;

readonly class RedirectToChild extends Redirector
{
	public function __construct(MutablePath|string $subPath = '',
								?Variables         $parameters = null,
								ResponseCode       $code = ResponseCode::SEE_OTHER)
	{
		parent::__construct(new RerouteToChild($subPath), $parameters, $code);
	}

}