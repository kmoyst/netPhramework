<?php

namespace netPhramework\authentication;

use netPhramework\common\Utils;

enum UserRole:int
{
	case VISITOR = 1;
	case STANDARD_USER = 10;
	case ADMIN = 20;

	public function friendlyName():string
	{
		return Utils::screamingSnakeToSpace($this->name);
	}
}
