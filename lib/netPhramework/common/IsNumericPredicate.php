<?php

namespace netPhramework\common;

class IsNumericPredicate implements StringPredicate
{
	public function test(string $value): bool
	{
		return is_numeric($value);
	}
}