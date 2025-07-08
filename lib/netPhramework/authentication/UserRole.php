<?php

namespace netPhramework\authentication;

use netPhramework\common\EnumToArray;
use netPhramework\common\Utils;

enum UserRole:int
{
	use EnumToArray;

	case VISITOR = 1;
	case STANDARD_USER = 10;
	case ADMINISTRATOR = 20;

	public function friendlyName():string
	{
		return Utils::screamingSnakeToSpace($this->name);
	}
}
