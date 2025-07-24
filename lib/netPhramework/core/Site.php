<?php

namespace netPhramework\core;

use netPhramework\runtime\RuntimeContext;
use netPhramework\runtime\Session;

interface Site
{
	public function generateApplication(
		Session $session, RuntimeContext $context):Application;
}
