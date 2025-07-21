<?php

namespace netPhramework\core;

use netPhramework\user\Session;

interface Site
{
	public function generateApplication(
		Session $session, RuntimeContext $context):Application;
}
