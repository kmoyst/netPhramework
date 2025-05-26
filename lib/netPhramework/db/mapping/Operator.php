<?php

namespace netPhramework\db\mapping;

use netPhramework\common\EnumToArray;

enum Operator:string
{
	use EnumToArray;

	case EQUAL = 'EQUALS';
	case CONTAINS = 'CONTAINS';
	case STARTS_WITH = 'STARTS_WITH';

	public function check(string $haystack, string $needle):bool
	{
		if($this === self::CONTAINS)
			return stristr($haystack, $needle);
		elseif($this === self::EQUAL)
			return $haystack == $needle;
		elseif($this === self::STARTS_WITH)
			return str_starts_with($haystack, $needle);
		else exit(1);
	}
}