<?php

namespace stubs;

class Site extends \netPhramework\core\Site
{
	public function getApplication(): Application
	{
		return new Application();
	}

}