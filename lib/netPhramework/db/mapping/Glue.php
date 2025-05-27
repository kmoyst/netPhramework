<?php

namespace netPhramework\db\mapping;

use netPhramework\common\EnumToArray;

enum Glue:int
{
	use EnumToArray;

	case AND = 1;
	case OR = 2;

	public function check(array $one, array $two):array
	{
		if($this === self::AND)
			return array_intersect($one, $two);
		else
			return array_merge($one, $two);
	}
}
