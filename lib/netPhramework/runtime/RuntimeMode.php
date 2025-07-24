<?php

namespace netPhramework\runtime;

enum RuntimeMode:string
{
	case PRODUCTION  = 'PRODUCTION';
	case DEVELOPMENT = 'DEVELOPMENT';
	case STAGING	 = 'STAGING';

	public function isDevelopment():bool
	{
		return $this === RuntimeMode::DEVELOPMENT;
	}
}
