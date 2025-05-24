<?php

namespace netPhramework\dispatching\dispatchers;

use netPhramework\common\Variables;
use netPhramework\core\ResponseCode;
use netPhramework\dispatching\Path;
use netPhramework\dispatching\relocators\RelocateToSibling;

readonly class DispatchToSibling extends Dispatcher
{
	public function __construct(Path|string $subPath = '',
								?Variables $parameters = null,
								ResponseCode $code = ResponseCode::SEE_OTHER)
	{
		parent::__construct(new RelocateToSibling($subPath),$parameters,$code);
	}

}