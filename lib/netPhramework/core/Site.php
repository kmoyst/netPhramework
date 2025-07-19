<?php

namespace netPhramework\core;

use netPhramework\user\Session;

interface Site
{
	public function getApplication(
		Session $session, RuntimeContext $context):Application;
}
