<?php

namespace netPhramework\db\mapping;

use netPhramework\common\EnumToArray;

enum Operator:string
{
	use EnumToArray;

	case EQUAL = '=';

}