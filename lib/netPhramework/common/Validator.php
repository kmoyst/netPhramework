<?php

namespace netPhramework\common;

interface Validator
{
	public function validate(?string $value):bool;
}