<?php

namespace netPhramework\common;

interface StringEvaluator
{
	public function evaluate(string $value):bool;
}