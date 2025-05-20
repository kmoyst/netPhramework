<?php

namespace netPhramework\core;

use netPhramework\authentication\Session;
use netPhramework\bootstrap\Environment;

interface RequestContext
{
	public function getSession():Session;
	public function getEnvironment():Environment;
	public function getCallbackKey():string;
}