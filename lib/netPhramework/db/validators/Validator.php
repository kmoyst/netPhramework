<?php

namespace netPhramework\db\validators;

interface Validator
{
	public function validate(?string $value):bool;
}