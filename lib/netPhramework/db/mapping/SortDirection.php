<?php

namespace netPhramework\db\mapping;

enum SortDirection:int
{
	case ASCENDING  = 1;
	case DESCENDING = 2;

	public static function toArray():array
	{
		$a = [];
		foreach(self::cases() as $case)
			$a[$case->value] = $case->name;
		return $a;
	}
}
