<?php

namespace netPhramework\common;

final class NotNullValidator implements Validator
{
	public function validate(?string $value): bool
	{
		return $value !== '' && $value !== null;
	}
}