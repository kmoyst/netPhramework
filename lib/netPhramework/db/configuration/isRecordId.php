<?php

namespace netPhramework\db\configuration;

use netPhramework\common\StringEvaluator;

class isRecordId implements StringEvaluator
{
	public function evaluate(string $value): bool
	{
		return is_numeric($value);
	}
}