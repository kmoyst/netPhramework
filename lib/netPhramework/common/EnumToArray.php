<?php

namespace netPhramework\common;

trait EnumToArray
{
	public static function toArray():array
	{
		$a = [];
		foreach(self::cases() as $case)
			$a[$case->value] = $case->name;
		return $a;
	}
}
