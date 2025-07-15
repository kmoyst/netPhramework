<?php

namespace netPhramework\http;

use netPhramework\core\Site;

abstract class HttpSite extends Site
{
	public function __construct()
	{
		parent::__construct(
			new HttpEnvironment(), new HttpInterpreter(),
			new HttpResponder(), new HttpServices());
	}
}