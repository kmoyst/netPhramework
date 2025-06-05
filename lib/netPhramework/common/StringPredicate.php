<?php

namespace netPhramework\common;

interface StringPredicate
{
	public function test(string $value):bool;
}