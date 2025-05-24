<?php

namespace netPhramework\dispatching\dispatchers;

use netPhramework\common\Variables;
use netPhramework\core\ResponseCode;
use netPhramework\dispatching\Path;
use netPhramework\dispatching\relocators\RelocateToChild;

readonly class DispatchToChild extends Dispatcher
{
	public function __construct(Path|string $subPath = '',
								?Variables $parameters = null,
								ResponseCode $code = ResponseCode::SEE_OTHER)
	{
		parent::__construct(new RelocateToChild($subPath), $parameters, $code);
	}

}