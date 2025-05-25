<?php

namespace netPhramework\dispatching\redirectors;

use netPhramework\common\Variables;
use netPhramework\core\ResponseCode;
use netPhramework\dispatching\MutablePath;
use netPhramework\dispatching\rerouters\RerouteToRoot;

readonly class RedirectToRoot extends Redirector
{
	public function __construct(MutablePath|string $subPath = '',
								?Variables         $parameters = null,
								ResponseCode       $code = ResponseCode::SEE_OTHER)
	{
		parent::__construct(new RerouteToRoot($subPath), $parameters, $code);
	}

}