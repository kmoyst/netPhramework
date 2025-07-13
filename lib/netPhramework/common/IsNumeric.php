<?php

namespace netPhramework\common;

class IsNumeric implements StringPredicate
{
	public function test(string $value): bool
	{
		return is_numeric($value);
	}
}