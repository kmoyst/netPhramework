<?php

namespace netPhramework\stubs;

readonly class Site extends \netPhramework\core\Site
{
	public function getApplication(): Application
	{
		return new Application();
	}

}