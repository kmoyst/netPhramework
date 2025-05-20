<?php

namespace netPhramework\bootstrap;

enum Environment:int
{
    case DEVELOPMENT = 50;
    case PRODUCTION = 100;

	public function inDevelopment():bool
	{
		return $this === self::DEVELOPMENT;
	}
}
