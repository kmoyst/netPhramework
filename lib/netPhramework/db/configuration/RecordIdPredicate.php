<?php

namespace netPhramework\db\configuration;

use netPhramework\common\StringPredicate;

class RecordIdPredicate implements StringPredicate
{
	public function test(string $value): bool
	{
		return is_numeric($value);
	}
}