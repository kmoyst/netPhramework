<?php

namespace netPhramework\core;

use netPhramework\user\Session;

abstract class Site
{
	public RuntimeContext $runtimeContext;
	public Session $session;

	abstract public Application $application {get;}
}
