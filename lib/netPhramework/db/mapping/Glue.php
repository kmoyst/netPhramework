<?php

namespace netPhramework\db\mapping;

use netPhramework\common\EnumToArray;

enum Glue:int
{
	use EnumToArray;

	case AND = 1;
	case OR = 2;
}
