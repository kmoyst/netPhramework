<?php

namespace netPhramework\db\mapping;

use netPhramework\common\EnumToArray;

enum SortDirection:int
{
	use EnumToArray;

	case ASCENDING  = 1;
	case DESCENDING = 2;

	public function toPhpValue():int
	{
		return $this === self::ASCENDING ? SORT_ASC : SORT_DESC;
	}
}
