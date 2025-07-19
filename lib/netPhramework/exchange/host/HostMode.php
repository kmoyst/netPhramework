<?php

namespace netPhramework\exchange\host;

enum HostMode:string
{
	case PRODUCTION = 'PRODUCTION';
	case DEVELOPMENT = 'DEVELOPMENT';
	case STAGING = 'STAGING';

	public function isDevelopment():bool
	{
		return $this === HostMode::DEVELOPMENT;
	}
}
