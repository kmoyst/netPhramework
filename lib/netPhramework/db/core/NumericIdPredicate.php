<?php

namespace netPhramework\db\core;

use netPhramework\common\StringPredicate;

class NumericIdPredicate implements StringPredicate
{
	public function test(string $value): bool
	{
		return is_numeric($value);
	}
}