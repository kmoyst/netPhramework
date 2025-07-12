<?php

namespace netPhramework\exchange;

use netPhramework\core\Application;

interface RequestContext extends ExchangeContext
{
	public RequestEnvironment $environment { get; }
	public function getApplication():Application;
}